@extends('layout.admin-panel')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border-l-8 border-blue-500 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                    <span class="mt-1 inline-flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> Data akun terdaftar
                    </span>
                </div>
                <div class="rounded-full bg-blue-100 p-3">
                    <i class="fas fa-users text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border-l-8 border-green-500 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-800">Rp{{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
                    <span class="mt-1 inline-flex items-center text-sm text-green-600">
                        <i class="fas fa-wallet mr-1"></i> Transaksi sukses hari ini
                    </span>
                </div>
                <div class="rounded-full bg-green-100 p-3">
                    <i class="fas fa-dollar-sign text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border-l-8 border-yellow-500 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tiket Terjual</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-800">{{ number_format($stats['tickets_sold']) }}</p>
                    <span class="mt-1 inline-flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> Akumulasi tiket sukses
                    </span>
                </div>
                <div class="rounded-full bg-yellow-100 p-3">
                    <i class="fas fa-shopping-cart text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border-l-8 border-red-500 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Kemping</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-800">{{ number_format($stats['camping_orders']) }}</p>
                    <span class="mt-1 text-xs text-gray-400">Pesanan paket camping</span>
                </div>
                <div class="rounded-full bg-red-100 p-3">
                    <i class="fas fa-campground text-xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-5 shadow-sm lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-700">
                    <i class="fas fa-chart-simple mr-2 text-indigo-500"></i>
                    Grafik Analitik
                </h2>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">7 hari terakhir</span>
            </div>
            <canvas id="salesChart" height="160" style="max-height: 320px;"></canvas>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-gray-700">
                <i class="fas fa-clock mr-2 text-indigo-500"></i>
                Aktivitas Terbaru
            </h2>

            <ul class="space-y-4">
                @forelse ($recentActivities as $activity)
                    <li class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full {{ $activity['icon_bg'] }} {{ $activity['icon_text'] }}">
                            <i class="fas {{ $activity['icon'] }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">{{ $activity['title'] }}</span>
                                {{ $activity['description'] }}
                            </p>
                            <span class="text-xs text-gray-400">{{ $activity['time']->diffForHumans() }}</span>
                        </div>
                    </li>
                @empty
                    <li class="rounded-lg bg-gray-50 px-4 py-5 text-sm text-gray-500">Belum ada aktivitas admin terbaru.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartElement = document.getElementById('salesChart');

            if (!chartElement) {
                return;
            }

            new Chart(chartElement.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($chartData),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.08)',
                        borderWidth: 3,
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0'
                            },
                            ticks: {
                                callback: function (value) {
                                    return 'Rp' + Number(value).toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
