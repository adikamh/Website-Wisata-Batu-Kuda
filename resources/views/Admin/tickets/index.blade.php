@extends('layout.admin-panel')

@section('title', 'Kelola Tiket')
@section('page_title', 'Tiket')

@section('admin_content')
    <div class="space-y-6">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-700">
                    <i class="fas fa-ticket-alt mr-2 text-indigo-500"></i>
                    Tiket
                </h2>
            </div>

            <div class="space-y-6 p-6">
                <div class="rounded-xl border p-5">
                    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-700">Kelola Tiket</h3>
                            <p class="text-sm text-gray-500">Tiket yang dibuat di sini akan tampil di halaman pemesanan user.</p>
                        </div>
                    </div>

                    @include('Admin.tickets.create')

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Tiket</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Deskripsi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Harga</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($tickets as $ticket)
                                    <tr class="align-top">
                                        @include('Admin.tickets.edit', ['ticket' => $ticket])
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada tiket. Tambahkan tiket pertama agar muncul di halaman pemesanan user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('Admin.tickets.reports')

                <div class="overflow-hidden rounded-xl border">
                    <div class="border-b bg-gray-50 px-5 py-4">
                        <h3 class="text-base font-bold text-gray-700">Tiket yang Sudah Dibeli</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Resi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Username</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($transactions as $transaction)
                                    @php($detail = $transaction->details->first())
                                    @php($rentalText = $transaction->rentalItems->isNotEmpty() ? $transaction->rentalItems->map(fn ($item) => $item->facility_name . ' x' . $item->quantity)->implode(', ') : '-')
                                    <tr class="align-top transition hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm font-semibold text-gray-900">INV-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">{{ $transaction->user->name ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <button type="button" data-ticket-detail-open data-jumlah="{{ $detail->quantity ?? 0 }}" data-masuk="{{ optional($detail?->start_date)->format('d/m/Y') ?? ($detail->start_date ?? '-') }}" data-keluar="{{ optional($detail?->end_date)->format('d/m/Y') ?? ($detail->end_date ?? '-') }}" data-nama="{{ $transaction->user->name ?? '-' }}" data-paket="{{ $detail?->tiketKategori?->nama_kategori ?? '-' }}" data-fasilitas="{{ $rentalText }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-indigo-700">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada tiket yang dipesan user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="ticketDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-receipt mr-2 text-indigo-500"></i> Detail Tiket</h3>
                <button type="button" data-ticket-detail-close class="text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4">
                    <span class="block text-xs uppercase text-gray-400">Jumlah Tiket</span>
                    <span id="modalJumlahTiket" class="font-semibold text-gray-800"></span>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <span class="block text-xs uppercase text-gray-400">Tanggal Masuk</span>
                    <span id="modalTanggalMasuk" class="font-semibold text-gray-800"></span>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <span class="block text-xs uppercase text-gray-400">Tanggal Keluar</span>
                    <span id="modalTanggalKeluar" class="font-semibold text-gray-800"></span>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <span class="block text-xs uppercase text-gray-400">Nama</span>
                    <span id="modalNama" class="font-semibold text-gray-800"></span>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <span class="block text-xs uppercase text-gray-400">Paket</span>
                    <span id="modalPaket" class="font-semibold text-gray-800"></span>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                    <span class="block text-xs uppercase text-gray-400">Fasilitas Sewa</span>
                    <span id="modalFasilitas" class="font-semibold text-gray-800"></span>
                </div>
            </div>
            <div class="flex justify-end border-t bg-gray-50 px-6 py-4">
                <button type="button" data-ticket-detail-close class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-900">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ticketCreateModal = document.getElementById('ticketCreateModal');
            const openTicketCreateButtons = document.querySelectorAll('[data-ticket-create-open]');
            const closeTicketCreateButtons = document.querySelectorAll('[data-ticket-create-close]');

            function openTicketCreateModal() {
                if (!ticketCreateModal) {
                    return;
                }

                ticketCreateModal.classList.remove('hidden');
                ticketCreateModal.classList.add('flex');
            }

            function closeTicketCreateModal() {
                if (!ticketCreateModal) {
                    return;
                }

                ticketCreateModal.classList.add('hidden');
                ticketCreateModal.classList.remove('flex');
            }

            openTicketCreateButtons.forEach(function (button) {
                button.addEventListener('click', openTicketCreateModal);
            });

            closeTicketCreateButtons.forEach(function (button) {
                button.addEventListener('click', closeTicketCreateModal);
            });

            ticketCreateModal?.addEventListener('click', function (event) {
                if (event.target === ticketCreateModal) {
                    closeTicketCreateModal();
                }
            });

            if (ticketCreateModal?.dataset.shouldOpen === 'true') {
                openTicketCreateModal();
            }

            const openTicketEditButtons = document.querySelectorAll('[data-ticket-edit-open]');
            const closeTicketEditButtons = document.querySelectorAll('[data-ticket-edit-close]');

            function openTicketEditModal(button) {
                const modal = document.getElementById(button.dataset.target);

                if (!modal) {
                    return;
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeTicketEditModal(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openTicketEditButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    openTicketEditModal(button);
                });
            });

            closeTicketEditButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const modal = button.closest('[id^="ticketEditModal-"]');

                    if (modal) {
                        closeTicketEditModal(modal);
                    }
                });
            });

            document.querySelectorAll('[id^="ticketEditModal-"]').forEach(function (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeTicketEditModal(modal);
                    }
                });
            });

            const ticketDetailModal = document.getElementById('ticketDetailModal');
            const openTicketDetailButtons = document.querySelectorAll('[data-ticket-detail-open]');
            const closeTicketDetailButtons = document.querySelectorAll('[data-ticket-detail-close]');

            function openTicketDetailModal(button) {
                document.getElementById('modalJumlahTiket').textContent = button.dataset.jumlah;
                document.getElementById('modalTanggalMasuk').textContent = button.dataset.masuk;
                document.getElementById('modalTanggalKeluar').textContent = button.dataset.keluar;
                document.getElementById('modalNama').textContent = button.dataset.nama;
                document.getElementById('modalPaket').textContent = button.dataset.paket;
                document.getElementById('modalFasilitas').textContent = button.dataset.fasilitas;
                ticketDetailModal.classList.remove('hidden');
                ticketDetailModal.classList.add('flex');
            }

            function closeTicketDetailModal() {
                ticketDetailModal.classList.add('hidden');
                ticketDetailModal.classList.remove('flex');
            }

            openTicketDetailButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    openTicketDetailModal(button);
                });
            });

            closeTicketDetailButtons.forEach(function (button) {
                button.addEventListener('click', closeTicketDetailModal);
            });

            ticketDetailModal?.addEventListener('click', function (event) {
                if (event.target === ticketDetailModal) {
                    closeTicketDetailModal();
                }
            });
        });
    </script>
@endpush
