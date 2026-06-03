<div class="border rounded-xl p-5">
    <h3 class="text-base font-bold text-gray-700 mb-4">Unduh Laporan</h3>
    <div class="flex flex-wrap gap-2">
        <form action="{{ route('admin.reports.visitors.pdf') }}" method="GET">
            <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition">
                <i class="fas fa-file-pdf mr-2"></i> Daftar Pengunjung PDF
            </button>
        </form>
        <form action="{{ route('admin.reports.visitors.email') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="inline-flex items-center px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm font-medium hover:bg-red-200 transition"
                data-swal-confirm
                data-swal-title="Kirim laporan ke email?"
                data-swal-text="Kirim laporan daftar pengunjung ke email admin yang sedang login?"
                data-swal-icon="question"
                data-swal-confirm-text="Ya, kirim"
                data-swal-cancel-text="Batal"
            >
                <i class="fas fa-envelope mr-2"></i> Kirim PDF ke Email Saya
            </button>
        </form>
        <form action="{{ route('admin.reports.finance.excel') }}" method="GET">
            <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition">
                <i class="fas fa-file-excel mr-2"></i> Keuangan Excel
            </button>
        </form>
        <form action="{{ route('admin.reports.finance.email') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="inline-flex items-center px-3 py-2 rounded-lg bg-green-100 text-green-700 text-sm font-medium hover:bg-green-200 transition"
                data-swal-confirm
                data-swal-title="Kirim laporan ke email?"
                data-swal-text="Kirim laporan keuangan ke email admin yang sedang login?"
                data-swal-icon="question"
                data-swal-confirm-text="Ya, kirim"
                data-swal-cancel-text="Batal"
            >
                <i class="fas fa-envelope mr-2"></i> Kirim Excel ke Email Saya
            </button>
        </form>
    </div>
</div>
