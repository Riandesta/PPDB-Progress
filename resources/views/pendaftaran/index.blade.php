<x-layout>
    <x-slot name="title">
        Daftar Pendaftar PPDB
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Pendaftar PPDB</h5>
                        <div>
                            <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Pendaftar
                            </a>
                            <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="filter_jurusan">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filter_status">
                                <option value="">Semua Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Diterima">Diterima</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filter_pembayaran">
                                <option value="">Status Pembayaran</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Lunas">Belum Lunas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="datatable-pendaftar">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendaftars as $index => $pendaftar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pendaftar->NISN }}</td>
                                    <td>{{ $pendaftar->nama }}</td>
                                    <td>{{ $pendaftar->jurusan->nama_jurusan }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $pendaftar->status_seleksi === 'Diterima' ? 'success' :
                                            ($pendaftar->status_seleksi === 'Ditolak' ? 'danger' : 'warning')
                                        }}">
                                            {{ $pendaftar->status_seleksi }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $pendaftar->administrasi->status_pembayaran === 'Lunas' ? 'success' : 'warning' }}">
                                            {{ $pendaftar->administrasi->status_pembayaran }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($pendaftar->rata_rata_nilai, 2) }}</td>
                                    <td>
                                            <a href="{{ route('pendaftaran.show', $item->id) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                            <a href="{{ route('pendaftaran.edit', $pendaftar->id) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('pendaftaran.destroy', $pendaftar->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $pendaftars->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#datatable-pendaftar').DataTable({
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: ['excel', 'pdf', 'print']
            });

            // Filter handling
            $('#filter_jurusan, #filter_status, #filter_pembayaran').change(function() {
                table.draw();
            });

            // Custom filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const jurusan = $('#filter_jurusan').val();
                const status = $('#filter_status').val();
                const pembayaran = $('#filter_pembayaran').val();

                if (
                    (jurusan === '' || data[3] === jurusan) &&
                    (status === '' || data[4].includes(status)) &&
                    (pembayaran === '' || data[5].includes(pembayaran))
                ) {
                    return true;
                }
                return false;
            });
        });

        // Export to Excel function
        function exportToExcel() {
            window.location.href = "{{ route('pendaftaran.export') }}";
        }
    </script>
    @endpush

</x-layout>
