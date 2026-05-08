<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Komentar;
use App\Models\LikeFoto;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Tampilkan halaman galeri utama dengan pagination, search, sort.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'terbaru');
        $q    = $request->query('q');

        if (! in_array($sort, ['terbaru', 'terpopuler', 'terbanyak_komentar'], true)) {
            $sort = 'terbaru';
        }

        $query = Gallery::query()
            ->withCount(['likes', 'komentars']);

        if (Auth::check()) {
            $query->withExists([
                'likes as liked_by_current_user' => fn ($likes) => $likes->where('user_id', Auth::id()),
            ]);
        }

        // Search by judul
        if ($q) {
            $query->where('judul_foto', 'like', '%' . $q . '%');
        }

        // Sort
        $query = match ($sort) {
            'terpopuler'         => $query->orderByDesc('likes_count'),
            'terbanyak_komentar' => $query->orderByDesc('komentars_count'),
            default              => $query->latest(), // terbaru
        };

        $fotos = $query->paginate(12)->withQueryString();

        $totalFoto     = Gallery::count();
        $totalLike     = LikeFoto::count();
        $totalKomentar = Komentar::count();
        $canUpload     = $this->isAdmin();

        return view('layout.gallery', compact(
            'fotos',
            'sort',
            'q',
            'totalFoto',
            'totalLike',
            'totalKomentar',
            'canUpload'
        ));
    }

    /**
     * Detail foto satu halaman (opsional, bisa untuk share link).
     */
    public function show(Gallery $gallery)
    {
        $gallery->load([
            'komentars.user',
            'likes',
        ]);

        $totalLike     = $gallery->totalLike();
        $totalKomentar = $gallery->totalKomentar();
        $isLiked       = Auth::check() ? $gallery->isLikedByUser(Auth::id()) : false;
        $canUpload     = $this->isAdmin();

        return view('layout.gallery-show', compact('gallery', 'totalLike', 'totalKomentar', 'isLiked', 'canUpload'));
    }

    /**
     * Upload foto baru (hanya admin).
     */
    public function store(Request $request)
    {
        $this->authorize_admin($request);

        $validated = $request->validate([
            'judul_foto' => 'required|string|max:120',
            'deskripsi'  => 'nullable|string|max:500',
            'gambar'     => 'required|image|mimes:jpeg,jpg,png,webp,heic,heif|max:16384', //16mb
        ], [
            'judul_foto.required' => 'Judul foto wajib diisi.',
            'gambar.required'     => 'Pilih foto terlebih dahulu.',
            'gambar.image'        => 'File yang diupload harus berupa gambar.',
            'gambar.max'          => 'Ukuran foto maksimal 16 MB.',
        ]);

        // Simpan file ke storage/app/public/gallery
        $path = $request->file('gambar')->store('gallery', 'public');

        $gallery = Gallery::create([
            'judul_foto'  => $validated['judul_foto'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'gambar_url'  => $path,
        ]);

        if ($request->expectsJson()) {
            return Response::json([
                'message' => 'Foto berhasil diupload.',
                'gallery' => $gallery,
            ], 201);
        }

        return Redirect::route('gallery.index')
            ->with('status', 'Foto berhasil diupload!');
    }

    /**
     * Update data foto dan opsional ganti gambar (hanya admin).
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize_admin($request);

        $validated = $request->validate([
            'judul_foto' => 'required|string|max:120',
            'deskripsi'  => 'nullable|string|max:500',
            'gambar'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'judul_foto.required' => 'Judul foto wajib diisi.',
            'gambar.image'        => 'File yang diupload harus berupa gambar.',
            'gambar.max'          => 'Ukuran foto maksimal 5 MB.',
        ]);

        $payload = [
            'judul_foto' => $validated['judul_foto'],
            'deskripsi'  => $validated['deskripsi'] ?? null,
        ];

        if ($request->hasFile('gambar')) {
            $this->deleteLocalImage($gallery->gambar_url);
            $payload['gambar_url'] = $request->file('gambar')->store('gallery', 'public');
        }

        $gallery->update($payload);
        $gallery->refresh();

        if ($request->expectsJson()) {
            return Response::json([
                'message' => 'Foto berhasil diperbarui.',
                'gallery' => $gallery,
            ]);
        }

        return Redirect::route('gallery.index')
            ->with('status', 'Foto berhasil diperbarui.');
    }

    /**
     * Hapus foto (hanya admin / owner — sesuaikan policy).
     */
    public function destroy(Request $request, Gallery $gallery)
    {
        $this->authorize_admin($request);

        $this->deleteLocalImage($gallery->gambar_url);

        $gallery->delete();

        if ($request->expectsJson()) {
            return Response::json(['message' => 'Foto berhasil dihapus.']);
        }

        return Redirect::route('gallery.index')
            ->with('status', 'Foto berhasil dihapus.');
    }

    /* ─────────────────────────────────────
       LIKE
    ───────────────────────────────────── */

    /**
     * Toggle like: POST = like, jika sudah di-like maka unlike.
     */
    public function like(Request $request, Gallery $gallery)
    {
        $this->authorize_login($request);

        $userId = Auth::id();

        $existing = LikeFoto::where('gallery_id', $gallery->id)
                            ->where('user_id', $userId)
                            ->first();

        if ($existing) {
            // Unlike
            $existing->delete();
            $liked = false;
        } else {
            // Like
            LikeFoto::create([
                'gallery_id' => $gallery->id,
                'user_id'    => $userId,
            ]);
            $liked = true;
        }

        $totalLike = $gallery->totalLike();

        return Response::json([
            'liked'      => $liked,
            'total_like' => $totalLike,
            'message'    => $liked ? 'Foto disukai.' : 'Like dibatalkan.',
        ]);
    }

    /* ─────────────────────────────────────
       KOMENTAR
    ───────────────────────────────────── */

    /**
     * Ambil semua komentar milik foto (JSON).
     */
    public function getKomentar(Gallery $gallery)
    {
        $komentars = Komentar::with('user:id,name')
            ->where('gallery_id', $gallery->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($k) => [
                'id'           => $k->id,
                'isi_komentar' => $k->isi_komentar,
                'created_at'   => $k->created_at->toIso8601String(),
                'user'         => [
                    'id'   => $k->user?->id,
                    'name' => $k->user?->name ?? 'Pengguna',
                ],
            ]);

        return Response::json(['data' => $komentars]);
    }

    /**
     * Simpan komentar baru.
     */
    public function storeKomentar(Request $request, Gallery $gallery)
    {
        $this->authorize_login($request);

        $validated = $request->validate([
            'isi_komentar' => 'required|string|max:500',
        ], [
            'isi_komentar.required' => 'Komentar tidak boleh kosong.',
        ]);

        $komentar = Komentar::create([
            'gallery_id'   => $gallery->id,
            'user_id'      => Auth::id(),
            'isi_komentar' => $validated['isi_komentar'],
        ]);

        $komentar->load('user:id,name');

        return Response::json([
            'message'  => 'Komentar berhasil ditambahkan.',
            'komentar' => [
                'id'           => $komentar->id,
                'isi_komentar' => $komentar->isi_komentar,
                'created_at'   => $komentar->created_at->toIso8601String(),
                'user'         => [
                    'id'   => $komentar->user?->id,
                    'name' => $komentar->user?->name ?? 'Pengguna',
                ],
            ],
        ], 201);
    }

    /**
     * Hapus komentar (hanya pemilik komentar).
     */
    public function destroyKomentar(Request $request, Komentar $komentar)
    {
        $this->authorize_login($request);

        if ($komentar->user_id !== Auth::id() && ! $this->isAdmin()) {
            return Response::json(['message' => 'Tidak diizinkan.'], 403);
        }

        $komentar->delete();

        return Response::json(['message' => 'Komentar dihapus.']);
    }

    /* ─────────────────────────────────────
       PRIVATE HELPERS
    ───────────────────────────────────── */

    /**
     * Tolak request jika belum login (tanpa middleware, cocok untuk API response).
     */
    private function authorize_login(Request $request): void
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                throw new HttpResponseException(
                    Response::json(['message' => 'Silakan login terlebih dahulu.'], 401)
                );
            }

            throw new AuthenticationException('Silakan login terlebih dahulu.', [], route('login'));
        }
    }

    /**
     * Pastikan hanya admin yang bisa upload dan hapus foto galeri.
     */
    private function authorize_admin(Request $request): void
    {
        $this->authorize_login($request);

        if (! $this->isAdmin()) {
            if ($request->expectsJson()) {
                throw new HttpResponseException(
                    Response::json(['message' => 'Hanya admin yang dapat mengelola foto galeri.'], 403)
                );
            }

            abort(403, 'Hanya admin yang dapat mengelola foto galeri.');
        }
    }

    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()?->role === 'admin';
    }

    private function deleteLocalImage(?string $path): void
    {
        $path = ltrim(trim((string) $path), '/');

        if (
            $path === ''
            || str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || ! Storage::disk('public')->exists($path)
        ) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
