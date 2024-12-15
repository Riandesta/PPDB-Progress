<x-layout>
    @slot('title')
        Manajemen Pembayaran
    @endslot

    <div class="row">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Data Pembayaran Siswa</h2>
                </header>
                <div class="panel-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="input-group">
                                    <select id="filter_status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="Lunas">Lunas</option>
                                        <option value="Belum Lunas">Belum Lunas</option>
                                    </select>
                                    <select id="filter_jenis" class="form-control">
                                        <option value="">Semua Jenis Pembayaran</option>
                                        <option value="pendaftaran">Pendaftaran</option>
                                        <option value="ppdb">PPDB</option>
                                        <option value="mpls">MPLS</option>
                                        <option value="awal_tahun">Awal Tahun</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped" id="datatable-pembayaran">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Total Pembayaran</th>
                                <th>Status</th>
                                <th>Detail Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($administrasi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->pendaftaran->nama }}</td>
                                <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item->status === 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>Pendaftaran: 
                                            <span class="badge {{ $item->is_pendaftaran_lunas ? 'bg-success' : 'bg-warning' }}">
                                                {{ $item->is_pendaftaran_lunas ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </li>
                                        <li>PPDB: 
                                            <span class="badge {{ $item->is_ppdb_lunas ? 'bg-success' : 'bg-warning' }}">
                                                {{ $item->is_ppdb_lunas ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </li>
                                        <li>MPLS: 
                                            <span class="badge {{ $item->is_mpls_lunas ? 'bg-success' : 'bg-warning' }}">
                                                {{ $item->is_mpls_lunas ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </li>
                                        <li>Awal Tahun: 
                                            <span class="badge {{ $item->is_awal_tahun_lunas ? 'bg-success' : 'bg-warning' }}">
                                                {{ $item->is_awal_tahun_lunas ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editPembayaran({{ $item->id }})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-info btn-sm" onclick="detailPembayaran({{ $item->id }})">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#datatable-pembayaran').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'pdf', 'print']
            });

            $('#filter_status, #filter_jenis').on('change', function() {
                table.draw();
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const status = $('#filter_status').val();
                const jenis = $('#filter_jenis').val();
                
                if (!status && !jenis) return true;
                
                const rowStatus = data[3];
                const rowJenis = data[4];
                
                const statusMatch = !status || rowStatus.includes(status);
                const jenisMatch = !jenis || rowJenis.toLowerCase().includes(jenis);
                
                return statusMatch && jenisMatch;
            });
        });

        function editPembayaran(id) {
            window.location.href = `/administrasi/pembayaran/${id}/edit`;
        }

        function detailPembayaran(id) {
            window.location.href = `/administrasi/pembayaran/${id}`;
        }
    </script>
    @endpush
</x-layout>
