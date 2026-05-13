<div class="space-y-4 p-6">
    <label class="block text-left">
        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Fasilitas</span>
        <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas', $facility?->nama_fasilitas) }}" placeholder="Contoh: Tenda / Hammock" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
    </label>

    <label class="block text-left">
        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Deskripsi</span>
        <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat fasilitas sewa" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi', $facility?->deskripsi) }}</textarea>
    </label>

    <div class="grid gap-4 sm:grid-cols-2">
        <label class="block text-left">
            <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Harga Sewa</span>
            <input type="number" name="harga" value="{{ old('harga', $facility ? (int) $facility->harga : null) }}" min="0" step="1000" placeholder="25000" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        </label>

        <label class="block text-left">
            <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Total Stok</span>
            <input type="number" name="total_stok" value="{{ old('total_stok', $facility?->total_stok) }}" min="0" step="1" placeholder="10" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        </label>
    </div>

    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" {{ old('is_active', $facility?->is_active ?? true) ? 'checked' : '' }}>
        Tampilkan di halaman pemesanan tiket
    </label>
</div>

<div class="flex justify-end gap-2 border-t bg-gray-50 px-6 py-4">
    <button type="button" data-facility-create-close data-facility-edit-close class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300">
        Batal
    </button>
    <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
        <i class="fas fa-save mr-2"></i> Simpan
    </button>
</div>
