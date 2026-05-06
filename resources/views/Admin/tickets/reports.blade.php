<div class="border rounded-xl p-5">
    <h3 class="text-base font-bold text-gray-700 mb-4">Unduh Laporan</h3>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.reports.visitors.pdf') }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition">
            <i class="fas fa-file-pdf mr-2"></i> Daftar Pengunjung PDF
        </a>
        <a href="{{ route('admin.reports.finance.excel') }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition">
            <i class="fas fa-file-excel mr-2"></i> Keuangan Excel
        </a>
    </div>
</div>
