<div class="mb-5 flex justify-end">
    <button type="button" data-ticket-create-open class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah Tiket
    </button>
</div>

<div id="ticketCreateModal" data-should-open="{{ $errors->any() && (old('nama_kategori') || old('deskripsi') || old('harga')) ? 'true' : 'false' }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 px-4">
    <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-ticket-alt mr-2 text-indigo-500"></i> Tambah Tiket</h3>
            <button type="button" data-ticket-create-close class="text-gray-400 hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.tickets.store') }}" method="POST">
            @csrf
            <div class="space-y-4 p-6">
                <label class="block">
                    <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Tiket</span>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" placeholder="Contoh: Tiket Masuk / Camping" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </label>

                <label class="block">
                    <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Deskripsi</span>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat tentang tiket ini" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi') }}</textarea>
                </label>

                <label class="block">
                    <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Harga</span>
                    <input type="number" name="harga" value="{{ old('harga') }}" min="0" step="1000" placeholder="15000" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </label>
            </div>

            <div class="flex justify-end gap-2 border-t bg-gray-50 px-6 py-4">
                <button type="button" data-ticket-create-close class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
