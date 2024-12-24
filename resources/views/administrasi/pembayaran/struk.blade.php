<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="text-center mb-4">
                        <h4 class="font-weight-bold">STRUK PEMBAYARAN PPDB</h4>
                        <p class="font-italic">{{ config('app.name') }}</p>
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <td class="font-weight-bold">No. Pembayaran</td>
                            <td>: {{ $pembayaran->no_pembayaran }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tanggal</td>
                            <td>: {{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nama Siswa</td>
                            <td>: {{ $pembayaran->administrasi->pendaftaran->nama }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jenis Pembayaran</td>
                            <td>: {{ ucfirst($pembayaran->jenis_pembayaran) }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jumlah Bayar</td>
                            <td>: Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Metode Pembayaran</td>
                            <td>: {{ ucfirst($pembayaran->metode_pembayaran) }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Status</td>
                            <td>: {{ ucfirst($pembayaran->status) }}</td>
                        </tr>
                    </table>

                    <div class="text-center mt-4">
                        <p class="font-weight-normal">Terima kasih atas pembayarannya</p>
                        <button class="btn btn-primary" onclick="window.print()">Cetak Struk</button>
                        <a href="{{ route('administrasi.pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style type="text/css" media="print">
    @media print {
        .btn { display: none; }
        .card { border: none; box-shadow: none; }
        .table td, .table th { padding: 8px; }
        .font-weight-bold { font-weight: bold; }
        .font-italic { font-style: italic; }
        .text-center { text-align: center; }
        .container-fluid, .card-body { padding: 10px; }
        .font-weight-normal { font-weight: normal; }
    }
</style>
@endpush
