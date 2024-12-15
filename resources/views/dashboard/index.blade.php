<x-layout>
    <x-slot:title>

    {{$title}}

    </x-slot:title>
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
            {{-- Statistik Cards --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pendaftar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $statistics['total_pendaftar'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <h4>Diterima</h4>
                        <h2 class="mb-0">{{ $statistics['total_diterima'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <h4>Total Pembayaran</h4>
                        <h2 class="mb-0">Rp {{ number_format($statistics['total_pembayaran'], 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <h4>Sisa Kuota</h4>
                        <h2 class="mb-0">{{ $statistics['sisa_kuota'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Statistik Pendaftaran</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pendaftaranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Pendaftar per Jurusan
                    </div>
                    <div class="card-body">
                        <canvas id="pendaftarPerJurusanChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Status Pembayaran
                    </div>
                    <div class="card-body">
                        <canvas id="statusPembayaranChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Chart for Pendaftar per Jurusan
        const pendaftarCtx = document.getElementById('pendaftarPerJurusanChart');
        new Chart(pendaftarCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($statistics['pendaftar_per_jurusan']->pluck('jurusan.nama_jurusan')) !!},
                datasets: [{
                    data: {!! json_encode($statistics['pendaftar_per_jurusan']->pluck('total')) !!},
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0']
                }]
            }
        });

        // Chart for Status Pembayaran
        const pembayaranCtx = document.getElementById('statusPembayaranChart');
        new Chart(pembayaranCtx, {
            type: 'bar', ['Lunas', 'Belum Lunas'],
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: [
                        {{ $statistics['pembayaran_lunas'] }},
                        {{ $statistics['pembayaran_belum_lunas'] }}
                    ],
                    backgroundColor: ['#198754', '#dc3545']
                }]
            }
        });
    </script>
    @endpush
</x-layout>
