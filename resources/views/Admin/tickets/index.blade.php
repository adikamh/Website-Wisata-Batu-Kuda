@extends('layout.admin-panel')

@section('title', 'Kelola Tiket')
@section('page_title', 'Tiket')
@section('hide_admin_inline_alerts', 'true')

@section('admin_content')
    <x-sweet-alert :assets="false" />

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
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <h3 class="text-base font-bold text-gray-700">Tiket yang Sudah Dibeli</h3>
                                <p class="mt-1 text-xs text-gray-500">
                                    Menampilkan {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi.
                                </p>
                            </div>

                            <form id="transactionFilterForm" action="{{ route('admin.tickets') }}" method="GET" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                                <label class="relative sm:w-72">
                                    <span class="sr-only">Cari resi atau nama</span>
                                    <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                                    <input
                                        type="search"
                                        name="search"
                                        id="transactionSearchInput"
                                        value="{{ $transactionFilters['search'] ?? '' }}"
                                        placeholder="Cari resi atau nama"
                                        class="w-full rounded-lg border-gray-300 py-2 pl-9 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </label>

                                <label class="sm:w-48">
                                    <span class="sr-only">Status approval</span>
                                    <select name="approval_status" id="transactionApprovalStatus" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="all" @selected(($transactionFilters['approval_status'] ?? 'all') === 'all')>Semua Status</option>
                                        <option value="pending" @selected(($transactionFilters['approval_status'] ?? 'all') === 'pending')>Belum di-approve</option>
                                        <option value="success" @selected(($transactionFilters['approval_status'] ?? 'all') === 'success')>Sudah di-approve</option>
                                    </select>
                                </label>

                                <label class="sm:w-36">
                                    <span class="sr-only">Jumlah data per halaman</span>
                                    <select name="per_page" id="transactionPerPage" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="10" @selected(($transactionFilters['per_page'] ?? 10) === 10)>10 data</option>
                                        <option value="25" @selected(($transactionFilters['per_page'] ?? 10) === 25)>25 data</option>
                                        <option value="50" @selected(($transactionFilters['per_page'] ?? 10) === 50)>50 data</option>
                                    </select>
                                </label>
                            </form>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Resi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Username</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($transactions as $transaction)
                                    @php($detail = $transaction->details->first())
                                    @php($rentalText = $transaction->rentalItems->isNotEmpty() ? $transaction->rentalItems->map(fn ($item) => $item->facility_name . ' x' . $item->quantity)->implode(', ') : '-')
                                    @php($statusStyles = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'success' => 'bg-green-100 text-green-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                        'expired' => 'bg-gray-100 text-gray-600',
                                    ])
                                    @php($statusLabels = [
                                        'pending' => 'Menunggu Approval',
                                        'success' => 'Disetujui',
                                        'failed' => 'Gagal',
                                        'expired' => 'Kedaluwarsa',
                                    ])
                                    <tr class="align-top transition hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm font-semibold text-gray-900">INV-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">{{ $transaction->user->name ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$transaction->status_pembayaran] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $statusLabels[$transaction->status_pembayaran] ?? ucfirst($transaction->status_pembayaran) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm font-semibold text-gray-900">
                                            Rp{{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <div class="flex flex-wrap gap-2">
                                                <button type="button" data-ticket-detail-open data-jumlah="{{ $detail->quantity ?? 0 }}" data-masuk="{{ optional($detail?->start_date)->format('d/m/Y') ?? ($detail->start_date ?? '-') }}" data-keluar="{{ optional($detail?->end_date)->format('d/m/Y') ?? ($detail->end_date ?? '-') }}" data-nama="{{ $transaction->user->name ?? '-' }}" data-paket="{{ $detail?->tiketKategori?->nama_kategori ?? '-' }}" data-fasilitas="{{ $rentalText }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-indigo-700">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </button>

                                                @if ($transaction->status_pembayaran === 'pending')
                                                    <form action="{{ route('admin.transactions.approve', $transaction) }}" method="POST" onsubmit="return confirm('Approve transaksi ini? Data akan masuk ke dashboard.');">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-green-700">
                                                            <i class="fas fa-check mr-1"></i> Approve
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada tiket yang dipesan user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col gap-3 border-t bg-gray-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-gray-500">
                            Menampilkan {{ $transactions->firstItem() ?? 0 }} sampai {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi
                        </p>

                        <div class="flex flex-wrap items-center gap-2">
                            @if ($transactions->onFirstPage())
                                <span class="inline-flex cursor-not-allowed items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-400">
                                    <i class="fas fa-chevron-left mr-2 text-xs"></i> Previous
                                </span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                                    <i class="fas fa-chevron-left mr-2 text-xs"></i> Previous
                                </a>
                            @endif

                            <span class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700">
                                Halaman {{ $transactions->currentPage() }} dari {{ $transactions->lastPage() }}
                            </span>

                            @if ($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                                    Next <i class="fas fa-chevron-right ml-2 text-xs"></i>
                                </a>
                            @else
                                <span class="inline-flex cursor-not-allowed items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-400">
                                    Next <i class="fas fa-chevron-right ml-2 text-xs"></i>
                                </span>
                            @endif
                        </div>
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

                if (modal.dataset.shouldOpen === 'true') {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
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

            const transactionFilterForm = document.getElementById('transactionFilterForm');
            const transactionSearchInput = document.getElementById('transactionSearchInput');
            const transactionApprovalStatus = document.getElementById('transactionApprovalStatus');
            const transactionPerPage = document.getElementById('transactionPerPage');
            let transactionSearchTimer;

            function submitTransactionFilters() {
                if (!transactionFilterForm) {
                    return;
                }

                transactionFilterForm.submit();
            }

            transactionSearchInput?.addEventListener('input', function () {
                clearTimeout(transactionSearchTimer);
                transactionSearchTimer = setTimeout(submitTransactionFilters, 450);
            });

            transactionApprovalStatus?.addEventListener('change', function () {
                clearTimeout(transactionSearchTimer);
                submitTransactionFilters();
            });

            transactionPerPage?.addEventListener('change', function () {
                clearTimeout(transactionSearchTimer);
                submitTransactionFilters();
            });
        });
    </script>
@endpush
