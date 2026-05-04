@extends('layout.admin')

@section('title', 'Admin Dashboard')

@section('content')

<div class="flex h-screen overflow-hidden">
    
    <!-- ======================== SIDEBAR / MENU UTAMA ======================== -->
    <aside class="sidebar w-72 bg-gradient-to-b from-[#0d2818] to-[#1a3c28] text-white flex-shrink-0 shadow-xl z-10 overflow-y-auto">
        <div class="p-6 border-b border-[#1a3c28]">
            <div class="flex items-center space-x-3">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7">
                    <path d="M4 26L12 12L18 20L22 14L28 26H4Z" fill="#74c69d" opacity="0.9"/>
                    <path d="M18 8C18 8 24 10 22 18C20 15 17 14 16 11C15 14 13 16 11 18C9 10 16 6 18 8Z" fill="#b7e4c7"/>
                </svg>
                <span class="text-2xl font-bold tracking-wide ml-2">Batu Kuda</span>
            </div>
            <p class="text-gray-400 text-sm mt-1">Panel Kontrol</p>
        </div>
        
        <nav class="mt-6 px-4 space-y-2">
            <!-- Menu Utama heading -->
            <div class="text-xs uppercase text-gray-500 tracking-wider font-semibold px-3 mb-2">Menu Utama</div>
            
            <a href="#kelola-user" data-admin-menu="kelola-user" class="admin-menu-link flex items-center space-x-3 px-3 py-2.5 rounded-lg bg-gray-700 bg-opacity-50 text-white transition-smooth hover:bg-gray-700">
                <i class="fas fa-users w-5"></i>
                <span>Kelola user</span>
            </a>
            
            <a href="#tiket" data-admin-menu="tiket" class="admin-menu-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 transition-smooth hover:bg-gray-700 hover:text-white">
                <i class="fas fa-boxes w-5"></i>
                <span>Tiket</span>
            </a>
        </nav>
        

    </aside>

    <!-- ======================== MAIN CONTENT ======================== -->
    <main class="flex-1 overflow-y-auto bg-gray-50">
        
        <!-- Top header -->
        <header class="bg-white shadow-sm sticky top-0 z-20 px-8 py-4 flex justify-between items-center border-b">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-gray-700 relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] px-1 rounded-full">3</span>
                </button>
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 leading-tight">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-[#40916c] flex items-center justify-center text-white">
                        <i class="fas fa-user-shield text-sm"></i>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-medium transition-smooth">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="p-6 md:p-8">
            <div id="kelola-user" data-admin-section="kelola-user">
            
            <!-- Stat Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Pengguna -->
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-8 border-blue-500 transition hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Pengguna</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">12,345</p>
                            <span class="inline-flex items-center text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> +5%</span>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Pendapatan Hari Ini -->
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-8 border-green-500 transition hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Pendapatan Hari Ini</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">$1,200</p>
                            <span class="inline-flex items-center text-red-600 text-sm mt-1"><i class="fas fa-arrow-down mr-1"></i> -2%</span>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Pesanan Baru -->
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-8 border-yellow-500 transition hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Tiket Terjual</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">78</p>
                            <span class="inline-flex items-center text-green-600 text-sm mt-1"><i class="fas fa-arrow-up mr-1"></i> +12%</span>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Tiket Support -->
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-8 border-red-500 transition hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Kemping</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">15</p>
                            <span class="text-gray-400 text-xs mt-1">perlu ditindaklanjuti</span>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-headset text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Analitik Penjualan + Aktivitas Terbaru (2 kolom) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Chart Penjualan Minggu Ini -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-700"><i class="fas fa-chart-simple mr-2 text-indigo-500"></i> Analitik Penjualan Minggu Ini</h2>
                        <select class="text-sm border rounded-lg px-2 py-1 bg-gray-50">
                            <option>Minggu Ini</option>
                            <option>Bulan Ini</option>
                        </select>
                    </div>
                    <canvas id="salesChart" height="200" style="max-height: 280px;"></canvas>
                </div>
                
                <!-- Aktivitas Terbaru -->
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h2 class="text-lg font-bold text-gray-700 mb-4"><i class="fas fa-clock mr-2 text-indigo-500"></i> Aktivitas Terbaru</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs">A</div>
                            <div>
                                <p class="text-sm text-gray-700"><span class="font-semibold">Admin</span> edit produk A</p>
                                <span class="text-xs text-gray-400">2 menit lalu</span>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xs">U</div>
                            <div>
                                <p class="text-sm text-gray-700"><span class="font-semibold">Pengguna</span> daftar baru</p>
                                <span class="text-xs text-gray-400">1 jam lalu</span>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-xs">E</div>
                            <div>
                                <p class="text-sm text-gray-700"><span class="font-semibold">Pengguna</span> edit produk B</p>
                                <span class="text-xs text-gray-400">3 jam lalu</span>
                            </div>
                        </li>
                        <li class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs">N</div>
                            <div>
                                <p class="text-sm text-gray-700"><span class="font-semibold">Pengguna</span> daftar baru</p>
                                <span class="text-xs text-gray-400">5 jam lalu</span>
                            </div>
                        </li>
                    </ul>
                    <button class="mt-4 text-indigo-600 text-sm font-medium hover:underline flex items-center">
                        Lihat semua aktivitas <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
            
            <!-- Tabel Pengguna (ID Pengguna | Nama | Role | Aktivitas Terakhir | Aksi) -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-700"><i class="fas fa-table-list mr-2 text-indigo-500"></i> Data Pengguna Terbaru</h2>
                    <button class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-plus mr-1"></i> Tambah Pengguna
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pengguna</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas Terakhir</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <!-- Baris 1 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#USR-1001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Andi Wijaya</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Admin</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Edit produk, 10 menit lalu</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <!-- Baris 2 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#USR-1002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Siti Nurhaliza</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Pengguna</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Login, 2 jam lalu</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <!-- Baris 3 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#USR-1003</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Budi Santoso</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Moderator</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Review produk, 1 hari lalu</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <!-- Baris 4 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#USR-1004</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Dewi Lestari</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Pengguna</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Daftar baru, 3 jam lalu</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3 border-t bg-gray-50 flex justify-between items-center">
                    <p class="text-sm text-gray-500">Menampilkan 4 dari 24 pengguna</p>
                    <div class="flex space-x-1">
                        <button class="px-3 py-1 border rounded-md text-sm bg-white hover:bg-gray-100">Prev</button>
                        <button class="px-3 py-1 border rounded-md text-sm bg-indigo-600 text-white">1</button>
                        <button class="px-3 py-1 border rounded-md text-sm bg-white hover:bg-gray-100">2</button>
                        <button class="px-3 py-1 border rounded-md text-sm bg-white hover:bg-gray-100">Next</button>
                    </div>
                </div>
            </div>
            </div>

            <!-- Menu Tiket -->
            <div id="tiket" data-admin-section="tiket" class="bg-white rounded-xl shadow-sm overflow-hidden hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-700"><i class="fas fa-ticket-alt mr-2 text-indigo-500"></i> Tiket</h2>
                </div>

                <div class="p-6 space-y-6">
                    <div class="border rounded-xl p-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                            <h3 class="text-base font-bold text-gray-700">Tiket untuk CRUD</h3>
                            <button type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                                <i class="fas fa-plus mr-2"></i> Tambah Tiket
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tiket</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">Tiket Masuk</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">Rp 15.000</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                                            <button type="button" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="border rounded-xl p-5">
                        <h3 class="text-base font-bold text-gray-700 mb-4">Unduh Laporan</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="inline-flex items-center px-3 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition">
                                <i class="fas fa-file-pdf mr-2"></i> Daftar Pengunjung PDF
                            </a>
                            <a href="#" class="inline-flex items-center px-3 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition">
                                <i class="fas fa-file-excel mr-2"></i> Keuangan Excel
                            </a>
                        </div>
                    </div>

                    <div class="border rounded-xl overflow-hidden">
                        <div class="px-5 py-4 border-b bg-gray-50">
                            <h3 class="text-base font-bold text-gray-700">Tiket yang Sudah Dibeli</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr class="hover:bg-gray-50 transition align-top">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">12/05/2026</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">INV-000123</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">andiwijaya</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <button type="button" data-ticket-detail-open data-jumlah="2" data-masuk="12/05/2026" data-keluar="13/05/2026" data-nama="Andi Wijaya" data-paket="Camping" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition align-top">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">14/05/2026</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">INV-000124</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">sitinur</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <button type="button" data-ticket-detail-open data-jumlah="4" data-masuk="14/05/2026" data-keluar="14/05/2026" data-nama="Siti Nurhaliza" data-paket="Tiket Masuk" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition align-top">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">15/05/2026</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">INV-000125</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">budisantoso</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <button type="button" data-ticket-detail-open data-jumlah="1" data-masuk="15/05/2026" data-keluar="16/05/2026" data-nama="Budi Santoso" data-paket="Camping" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition align-top">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">17/05/2026</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">INV-000126</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">dewilestari</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <button type="button" data-ticket-detail-open data-jumlah="3" data-masuk="17/05/2026" data-keluar="18/05/2026" data-nama="Dewi Lestari" data-paket="Family Camp" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Detail Tiket -->
<div id="ticketDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 px-4">
    <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-receipt mr-2 text-indigo-500"></i> Detail Tiket</h3>
            <button type="button" data-ticket-detail-close class="text-gray-400 hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6">
            <div class="rounded-lg bg-gray-50 p-4">
                <span class="block text-xs text-gray-400 uppercase">Jumlah Tiket</span>
                <span id="modalJumlahTiket" class="font-semibold text-gray-800">2</span>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <span class="block text-xs text-gray-400 uppercase">Tanggal Masuk</span>
                <span id="modalTanggalMasuk" class="font-semibold text-gray-800">12/05/2026</span>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <span class="block text-xs text-gray-400 uppercase">Tanggal Keluar</span>
                <span id="modalTanggalKeluar" class="font-semibold text-gray-800">13/05/2026</span>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <span class="block text-xs text-gray-400 uppercase">Nama</span>
                <span id="modalNama" class="font-semibold text-gray-800">Andi Wijaya</span>
            </div>
            <div class="rounded-lg bg-gray-50 p-4 sm:col-span-2">
                <span class="block text-xs text-gray-400 uppercase">Paket</span>
                <span id="modalPaket" class="font-semibold text-gray-800">Camping</span>
            </div>
        </div>
        <div class="flex justify-end border-t bg-gray-50 px-6 py-4">
            <button type="button" data-ticket-detail-close class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm font-medium hover:bg-gray-900 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- CHART INIT SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuLinks = document.querySelectorAll('[data-admin-menu]');
        const sections = document.querySelectorAll('[data-admin-section]');

        function showSection(sectionName) {
            sections.forEach(function (section) {
                section.classList.toggle('hidden', section.dataset.adminSection !== sectionName);
            });

            menuLinks.forEach(function (link) {
                const isActive = link.dataset.adminMenu === sectionName;
                link.classList.toggle('bg-gray-700', isActive);
                link.classList.toggle('bg-opacity-50', isActive);
                link.classList.toggle('text-white', isActive);
                link.classList.toggle('text-gray-300', !isActive);
            });
        }

        menuLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const sectionName = link.dataset.adminMenu;
                showSection(sectionName);
                history.replaceState(null, '', '#' + sectionName);
            });
        });

        if (window.location.hash === '#tiket') {
            showSection('tiket');
        }

        const ticketDetailModal = document.getElementById('ticketDetailModal');
        const openTicketDetailButtons = document.querySelectorAll('[data-ticket-detail-open]');
        const closeTicketDetailButtons = document.querySelectorAll('[data-ticket-detail-close]');
        const modalJumlahTiket = document.getElementById('modalJumlahTiket');
        const modalTanggalMasuk = document.getElementById('modalTanggalMasuk');
        const modalTanggalKeluar = document.getElementById('modalTanggalKeluar');
        const modalNama = document.getElementById('modalNama');
        const modalPaket = document.getElementById('modalPaket');

        function openTicketDetailModal(button) {
            modalJumlahTiket.textContent = button.dataset.jumlah;
            modalTanggalMasuk.textContent = button.dataset.masuk;
            modalTanggalKeluar.textContent = button.dataset.keluar;
            modalNama.textContent = button.dataset.nama;
            modalPaket.textContent = button.dataset.paket;
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

        ticketDetailModal.addEventListener('click', function (event) {
            if (event.target === ticketDetailModal) {
                closeTicketDetailModal();
            }
        });

        const ctx = document.getElementById('salesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [
                    {
                        label: 'Penjualan (USD)',
                        data: [1250, 1420, 1380, 1650, 1820, 2100, 1950],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { boxWidth: 12, font: { size: 12 } }
                    },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e2e8f0' },
                        title: { display: true, text: 'Pendapatan ($)', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        title: { display: true, text: 'Hari', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
</body>
</html>
