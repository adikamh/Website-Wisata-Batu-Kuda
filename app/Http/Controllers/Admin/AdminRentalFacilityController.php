<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRentalFacilityController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $facilities = RentalFacility::query()
            ->latest()
            ->get();

        return view('Admin.facilities.index', compact('facilities'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $this->validateFacility($request);
        $validated['stok_tersedia'] = $validated['total_stok'];
        $validated['is_active'] = $request->boolean('is_active', true);

        RentalFacility::create($validated);

        return redirect()
            ->route('admin.facilities')
            ->with('status', 'Fasilitas sewa berhasil ditambahkan.');
    }

    public function update(Request $request, RentalFacility $facility)
    {
        $this->authorizeAdmin();

        $validated = $this->validateFacility($request);
        $usedStock = max(0, $facility->total_stok - $facility->stok_tersedia);

        if ($validated['total_stok'] < $usedStock) {
            return back()
                ->withInput()
                ->withErrors([
                    'total_stok' => 'Total stok tidak boleh lebih kecil dari stok yang sudah tersewa (' . $usedStock . ').',
                ]);
        }

        $validated['stok_tersedia'] = $validated['total_stok'] - $usedStock;
        $validated['is_active'] = $request->boolean('is_active');

        $facility->update($validated);

        return redirect()
            ->route('admin.facilities')
            ->with('status', 'Fasilitas sewa berhasil diperbarui.');
    }

    public function destroy(RentalFacility $facility)
    {
        $this->authorizeAdmin();

        $facility->delete();

        return redirect()
            ->route('admin.facilities')
            ->with('status', 'Fasilitas sewa berhasil dihapus.');
    }

    private function validateFacility(Request $request): array
    {
        return $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'harga' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'total_stok' => ['required', 'integer', 'min:0', 'max:100000'],
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'harga.required' => 'Harga sewa wajib diisi.',
            'harga.numeric' => 'Harga sewa harus berupa angka.',
            'total_stok.required' => 'Total stok wajib diisi.',
            'total_stok.integer' => 'Total stok harus berupa angka bulat.',
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_if(! Auth::check() || Auth::user()->role !== 'admin', 403);
    }
}
