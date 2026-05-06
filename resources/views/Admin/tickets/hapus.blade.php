<form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tiket ini?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus tiket">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>
