<td class="px-4 py-4 text-sm text-gray-700">
    {{ $ticket->nama_kategori }}
</td>
<td class="px-4 py-4 text-sm text-gray-600">
    {{ $ticket->deskripsi ?: '-' }}
</td>
<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
    Rp {{ number_format($ticket->harga, 0, ',', '.') }}
</td>
<td class="px-4 py-4 whitespace-nowrap text-sm">
    <button type="button" data-ticket-edit-open data-target="ticketEditModal-{{ $ticket->id }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit tiket">
        <i class="fas fa-edit"></i>
    </button>
    @include('Admin.tickets.hapus')

    <div id="ticketEditModal-{{ $ticket->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 px-4">
        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-edit mr-2 text-indigo-500"></i> Edit Tiket</h3>
                <button type="button" data-ticket-edit-close class="text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4 p-6">
                    <label class="block text-left">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Tiket</span>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $ticket->nama_kategori) }}" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </label>

                    <label class="block text-left">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Deskripsi</span>
                        <textarea name="deskripsi" rows="3" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $ticket->deskripsi) }}</textarea>
                    </label>

                    <label class="block text-left">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Harga</span>
                        <input type="number" name="harga" value="{{ old('harga', (int) $ticket->harga) }}" min="0" step="1000" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </label>
                </div>

                <div class="flex justify-end gap-2 border-t bg-gray-50 px-6 py-4">
                    <button type="button" data-ticket-edit-close class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</td>
