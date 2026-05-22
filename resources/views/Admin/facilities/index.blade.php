@extends('layout.admin-panel')

@section('title', 'Kelola Fasilitas Sewa')
@section('page_title', 'Fasilitas Sewa')
@section('hide_admin_inline_alerts', 'true')

@section('admin_content')
    <x-sweet-alert :assets="false" />

    <div class="space-y-6">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b bg-gray-50 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-700">
                    <i class="fas fa-campground mr-2 text-emerald-600"></i>
                    Fasilitas Sewa
                </h2>
            </div>

            <div class="space-y-6 p-6">
                <div class="rounded-xl border p-5">
                    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-700">Kelola Tenda dan Hammock</h3>
                            <p class="text-sm text-gray-500">Fasilitas aktif akan muncul saat user membeli tiket. Stok otomatis berkurang saat dipesan.</p>
                        </div>
                        <button type="button" data-facility-create-open class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                            <i class="fas fa-plus mr-2"></i> Tambah Fasilitas
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Deskripsi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Harga</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Stok</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($facilities as $facility)
                                    <tr class="align-top">
                                        <td class="px-4 py-4 text-sm font-semibold text-gray-800">{{ $facility->nama_fasilitas }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-600">{{ $facility->deskripsi ?: '-' }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">Rp {{ number_format($facility->harga, 0, ',', '.') }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">{{ $facility->stok_tersedia }} / {{ $facility->total_stok }}</td>
                                        <td class="px-4 py-4 text-sm">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $facility->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $facility->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm">
                                            <button type="button" data-facility-edit-open data-target="facilityEditModal-{{ $facility->id }}" class="mr-3 text-indigo-600 hover:text-indigo-900" title="Edit fasilitas">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Hapus fasilitas"
                                                    data-swal-confirm
                                                    data-swal-title="Hapus fasilitas sewa ini?"
                                                    data-swal-text="Fasilitas {{ $facility->nama_fasilitas }} akan dihapus dari pilihan sewa user."
                                                    data-swal-confirm-text="Ya, hapus"
                                                    data-swal-cancel-text="Batal"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                            <div id="facilityEditModal-{{ $facility->id }}" data-should-open="{{ old('facility_form') === ('edit-' . $facility->id) ? 'true' : 'false' }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 px-4">
                                                <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl">
                                                    <div class="flex items-center justify-between border-b px-6 py-4">
                                                        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-edit mr-2 text-emerald-600"></i> Edit Fasilitas</h3>
                                                        <button type="button" data-facility-edit-close class="text-gray-400 hover:text-gray-700">
                                                            <i class="fas fa-times text-lg"></i>
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('admin.facilities.update', $facility) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="facility_form" value="edit-{{ $facility->id }}">
                                                        @include('Admin.facilities.partials.form', [
                                                            'facility' => $facility,
                                                            'useOldInput' => old('facility_form') === ('edit-' . $facility->id),
                                                        ])
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada fasilitas sewa. Tambahkan tenda atau hammock agar user bisa menyewa saat membeli tiket.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="facilityCreateModal" data-should-open="{{ old('facility_form') === 'create' ? 'true' : 'false' }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 px-4">
        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-campground mr-2 text-emerald-600"></i> Tambah Fasilitas</h3>
                <button type="button" data-facility-create-close class="text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.facilities.store') }}" method="POST">
                @csrf
                <input type="hidden" name="facility_form" value="create">
                @include('Admin.facilities.partials.form', [
                    'facility' => null,
                    'useOldInput' => old('facility_form') === 'create',
                ])
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createModal = document.getElementById('facilityCreateModal');
            const openCreateButtons = document.querySelectorAll('[data-facility-create-open]');
            const closeCreateButtons = document.querySelectorAll('[data-facility-create-close]');

            function openModal(modal) {
                modal?.classList.remove('hidden');
                modal?.classList.add('flex');
            }

            function closeModal(modal) {
                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            }

            openCreateButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    openModal(createModal);
                });
            });

            closeCreateButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    closeModal(createModal);
                });
            });

            createModal?.addEventListener('click', function (event) {
                if (event.target === createModal) {
                    closeModal(createModal);
                }
            });

            if (createModal?.dataset.shouldOpen === 'true') {
                openModal(createModal);
            }

            document.querySelectorAll('[data-facility-edit-open]').forEach(function (button) {
                button.addEventListener('click', function () {
                    openModal(document.getElementById(button.dataset.target));
                });
            });

            document.querySelectorAll('[data-facility-edit-close]').forEach(function (button) {
                button.addEventListener('click', function () {
                    closeModal(button.closest('[id^="facilityEditModal-"]'));
                });
            });

            document.querySelectorAll('[id^="facilityEditModal-"]').forEach(function (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });

                if (modal.dataset.shouldOpen === 'true') {
                    openModal(modal);
                }
            });
        });
    </script>
@endpush
