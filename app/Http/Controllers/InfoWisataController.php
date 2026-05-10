<?php

namespace App\Http\Controllers;

use App\Models\InfoWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class InfoWisataController
{
    // ── Helper: cek admin ────────────────────────────────
    private function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    // ── READ: semua user bisa lihat ──────────────────────
    public function index()
    {
        $sections = InfoWisata::ordered()->get();
        return view('layout.infowisata', compact('sections'));
    }

    // ── CREATE seksi baru (admin only) ───────────────────
    public function store(Request $request)
    {
        abort_unless($this->isAdmin(), 403);

        $data = $request->validate([
            'judul'     => 'required|string|max:150',
            'kategori'  => 'nullable|string|max:80',
            'icon'      => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string|max:600',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        InfoWisata::create(array_merge($data, ['poin' => [], 'gambar' => []]));

        return back()->with('success', "Seksi \"{$data['judul']}\" berhasil ditambahkan.");
    }

    // ── UPDATE seksi (admin only) ─────────────────────────
    public function update(Request $request, InfoWisata $infoWisata)
    {
        abort_unless($this->isAdmin(), 403);

        $data = $request->validate([
            'judul'     => 'required|string|max:150',
            'kategori'  => 'nullable|string|max:80',
            'icon'      => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string|max:600',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $infoWisata->update($data);

        return back()->with('success', "Seksi \"{$data['judul']}\" berhasil diperbarui.");
    }

    // ── DELETE seksi (admin only) ─────────────────────────
    public function destroy(InfoWisata $infoWisata)
    {
        abort_unless($this->isAdmin(), 403);
        $judul = $infoWisata->judul;
        $infoWisata->delete();
        return back()->with('success', "Seksi \"{$judul}\" berhasil dihapus.");
    }
    public function storePoin(Request $request, InfoWisata $infoWisata)
    {
        abort_unless($this->isAdmin(), 403);

        $data = $request->validate([
            'judul' => 'nullable|string|max:200',
            'isi'   => 'nullable|string|max:500',
        ]);

        $poin   = $infoWisata->poin ?? [];
        $poin[] = ['judul' => $data['judul'] ?? '', 'isi' => $data['isi'] ?? ''];
        $infoWisata->update(['poin' => $poin]);

        return back()->with('success', 'Poin berhasil ditambahkan.');
    }

    public function updatePoin(Request $request, InfoWisata $infoWisata, int $index)
    {
        abort_unless($this->isAdmin(), 403);

        $data = $request->validate([
            'judul' => 'nullable|string|max:200',
            'isi'   => 'nullable|string|max:500',
        ]);

        $poin = $infoWisata->poin ?? [];
        if (!isset($poin[$index])) abort(404);

        $poin[$index] = ['judul' => $data['judul'] ?? '', 'isi' => $data['isi'] ?? ''];
        $infoWisata->update(['poin' => array_values($poin)]);

        return back()->with('success', 'Poin berhasil diperbarui.');
    }

    public function destroyPoin(InfoWisata $infoWisata, int $index)
    {
        abort_unless($this->isAdmin(), 403);

        $poin = $infoWisata->poin ?? [];
        if (!isset($poin[$index])) abort(404);

        array_splice($poin, $index, 1);
        $infoWisata->update(['poin' => array_values($poin)]);

        return back()->with('success', 'Poin berhasil dihapus.');
    }

    public function destroyGambar(InfoWisata $infoWisata, int $index)
    {
        abort_unless($this->isAdmin(), 403);

        $gambar = $infoWisata->gambar ?? [];
        if (!isset($gambar[$index])) abort(404);

        array_splice($gambar, $index, 1);
        $infoWisata->update(['gambar' => array_values($gambar)]);

        return back()->with('success', 'Gambar berhasil dihapus.');
    }
}