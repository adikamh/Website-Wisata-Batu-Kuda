<form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <button
        type="submit"
        class="text-red-600 hover:text-red-900"
        title="Hapus tiket"
        data-swal-confirm
        data-swal-title="Hapus tiket ini?"
        data-swal-text="Tiket yang dihapus tidak akan tampil lagi di halaman pemesanan user."
        data-swal-confirm-text="Ya, hapus"
        data-swal-cancel-text="Batal"
    >
        <i class="fas fa-trash-alt"></i>
    </button>
</form>
