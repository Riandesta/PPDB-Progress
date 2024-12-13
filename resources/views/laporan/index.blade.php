<x-layout>
    <x-slot name="title">
        Laporan Administrasi PPDB
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Laporan Administrasi PPDB</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Total Biaya</th>
                            <th>Total Bayar</th>
                            <th>Sisa Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($administrasi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->calonSiswa->nama }}</td>
                                <td>Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->sisa_pembayaran, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                        {{ $item->status_pembayaran }}
                                    </span>
                                </td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>Rp {{ number_format($administrasi->sum('total_biaya'), 0, ',', '.') }}</strong></td>
                            <td><strong>Rp {{ number_format($administrasi->sum('total_bayar'), 0, ',', '.') }}</strong></td>
                            <td><strong>Rp {{ number_format($administrasi->sum('sisa_pembayaran'), 0, ',', '.') }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-layout>
