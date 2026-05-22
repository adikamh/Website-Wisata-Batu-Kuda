@php
    $useOldInput = old('modal_target') === $modalId;
    $selectedRole = $useOldInput ? old('role', 'user') : ($user->role ?? 'user');
    $isVerified = $useOldInput ? old('is_verified') : ($user->is_verified ?? false);
@endphp

<div id="{{ $modalId }}" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6">
    <div class="max-h-full w-full max-w-2xl overflow-y-auto rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h3 class="text-lg font-bold text-gray-800">{{ $title }}</h3>
            <button type="button" data-modal-close class="text-gray-400 transition hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form action="{{ $action }}" method="POST" class="space-y-5 p-6">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif
            <input type="hidden" name="modal_target" value="{{ $modalId }}">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ $useOldInput ? old('name') : ($user->name ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Username</label>
                    <input type="text" name="username" value="{{ $useOldInput ? old('username') : ($user->username ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ $useOldInput ? old('email') : ($user->email ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Role</label>
                    <select name="role" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
                        @foreach (['admin' => 'Admin', 'moderator' => 'Moderator', 'user' => 'User'] as $value => $label)
                            <option value="{{ $value }}" @selected($selectedRole === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                    <input type="text" name="Phone" value="{{ $useOldInput ? old('Phone') : ($user->Phone ?? '') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Status Verifikasi</label>
                    <label class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700">
                        <input type="checkbox" name="is_verified" value="1" @checked($isVerified)>
                        Tandai sebagai terverifikasi
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Alamat</label>
                    <textarea name="Address" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none">{{ $useOldInput ? old('Address') : ($user->Address ?? '') }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Password {{ $user ? '(opsional)' : '' }}</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none" {{ $user ? '' : 'required' }}>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:outline-none" {{ $user ? '' : 'required' }}>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t pt-5">
                <button type="button" data-modal-close class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
