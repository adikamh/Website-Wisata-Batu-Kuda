@extends('layout.admin-panel')

@section('title', 'Data Pengguna')
@section('page_title', 'Data Pengguna')

@section('admin_content')
    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b bg-gray-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-700">
                    <i class="fas fa-table-list mr-2 text-indigo-500"></i>
                    Data Pengguna
                </h2>
                <p class="mt-1 text-sm text-gray-500">Kelola akun pengguna dari satu halaman yang lebih fokus.</p>
            </div>
            <button type="button" data-modal-open="createUserModal" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                <i class="fas fa-plus mr-1"></i> Tambah Pengguna
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">#USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{{ $user->username }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $roleStyles = [
                                        'admin' => 'bg-blue-100 text-blue-800',
                                        'moderator' => 'bg-purple-100 text-purple-800',
                                        'user' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $roleStyles[$user->role] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>{{ mask_phone($user->Phone) ?: '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $user->is_verified ? 'Terverifikasi' : 'Belum verifikasi' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <button type="button" data-modal-open="editUserModal-{{ $user->id }}" class="mr-3 text-indigo-600 transition hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 transition hover:text-red-900" {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t bg-gray-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
            </p>
            {{ $users->links() }}
        </div>
    </div>

    @include('Admin.users.partials.form-modal', [
        'modalId' => 'createUserModal',
        'title' => 'Tambah Pengguna',
        'action' => route('admin.users.store'),
        'user' => null,
        'method' => 'POST',
    ])

    @foreach ($users as $user)
        @include('Admin.users.partials.form-modal', [
            'modalId' => 'editUserModal-' . $user->id,
            'title' => 'Edit Pengguna',
            'action' => route('admin.users.update', $user),
            'user' => $user,
            'method' => 'PUT',
        ])
    @endforeach
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openButtons = document.querySelectorAll('[data-modal-open]');
            const closeButtons = document.querySelectorAll('[data-modal-close]');
            const modalFromValidation = @json(old('modal_target'));

            function openModal(id) {
                const modal = document.getElementById(id);

                if (!modal) {
                    return;
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    openModal(button.dataset.modalOpen);
                });
            });

            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const modal = button.closest('[data-modal-root]');

                    if (modal) {
                        closeModal(modal);
                    }
                });
            });

            document.querySelectorAll('[data-modal-root]').forEach(function (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            if (modalFromValidation) {
                openModal(modalFromValidation);
            }
        });
    </script>
@endpush
