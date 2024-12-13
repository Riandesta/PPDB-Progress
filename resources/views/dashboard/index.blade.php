<x-layout>
    <x-slot name="title">
        Dashboard PPDB
    </x-slot>

    <div class="row">
        <!-- Statistik Utama -->
        <div class="col-md-12">
            <div class="row">
                <!-- Total Pendaftar -->
                <div class="col-md-3">
                    <section class="panel panel-featured-left panel-featured-primary">
                        <div class="panel-body">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-primary">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">Total Pendaftar</h4>
                                        <div class="info">
                                            <strong class="amount">{{ $statistics['total_pendaftar'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Pendaftar Diterima -->
                <div class="col-md-3">
                    <section class="panel panel-featured-left panel-featured-success">
                        <div class="panel-body">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-success">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">Diterima</h4>
                                        <div class="info">
                                            <strong class="amount">{{ $statistics['total_diterima'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Total Pembayaran -->
                <div class="col-md-3">
                    <section class="panel panel-featured-left panel-featured-quaternary">
                        <div class="panel-body">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-quaternary">
                                        <i class="fa fa-money"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">Total Pembayaran</h4>
                                        <div class="info">
                                            <strong class="amount">Rp {{ number_format($statistics['total_pembayaran'], 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Sisa Kuota -->
                <div class="col-md-3">
                    <section class="panel panel-featured-left panel-featured-secondary">
                        <div class="panel-body">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-secondary">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">Sisa Kuota</h4>
                                        <div class="info">
                                            <strong class="amount">{{ $statistics['sisa_kuota'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Detail -->
    <div class="row">
        <!-- Grafik Pendaftar per Jurusan -->
        <div class="col-md-6">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Pendaftar per Jurusan</h2>
                </header>
                <div class="panel-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="chartJurusan"></canvas>
                    </div>
                </div>
            </section>
        </div>

        <!-- Tabel Status Pembayaran -->
        <div class="col-md-6">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Status Pembayaran</h2>
                </header>
                <div class="panel-body">
                    <table class="table table-bordered table-striped mb-none">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Lunas</td>
                                <td>{{ $statistics['pembayaran_lunas'] ?? 0 }}</td>
                                <td>{{ $statistics['persentase_lunas'] ?? '0%' }}</td>
                            </tr>
                            <tr>
                                <td>Belum Lunas</td>
                                <td>{{ $statistics['pembayaran_belum_lunas'] ?? 0 }}</td>
                                <td>{{ $statistics['persentase_belum_lunas'] ?? '0%' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk grafik jurusan
        const jurusanData = @json($statistics['pendaftar_per_jurusan']);

        // Inisialisasi grafik
        const ctx = document.getElementById('chartJurusan').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: jurusanData.map(item => item.jurusan.nama),
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: jurusanData.map(item => item.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-layout>
