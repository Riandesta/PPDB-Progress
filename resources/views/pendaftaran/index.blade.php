<x-layout>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Pendaftar</h5>
                <div>
                    <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pendaftar
                    </a>
                    <a href="{{ route('pendaftaran.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="filter_jurusan">
                            <option value="">Semua Jurusan</option>
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filter_status">
                            <option value="">Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Lulus">Lulus</option>
                            <option value="Tidak Lulus">Tidak Lulus</option>
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

                <!-- Table -->
                <div class="table-responsive">
                    <table id="datatable-pendaftar" class="table table-bordered table-striped">
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
                            @foreach ($pendaftars as $index => $pendaftar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pendaftar->NISN }}</td>
                                    <td>{{ $pendaftar->nama }}</td>
                                    <td>{{ $pendaftar->jurusan->nama_jurusan }}</td>
                                    <td>
                                        <span class="badge bg-{{ $pendaftar->status_seleksi === 'Lulus' ? 'success' : ($pendaftar->status_seleksi === 'Tidak Lulus' ? 'danger' : 'warning') }}">
                                            {{ $pendaftar->status_seleksi }}
                                        </span>
                                    </td>
                                    <td>{{ $pendaftar->administrasi->status_pembayaran }}</td>
                                    <td>{{ number_format($pendaftar->rata_rata_nilai, 2) }}</td>
                                        <td class="text-end">
                                            <span class="dropdown">
                                                <button 
                                                    class="btn dropdown-toggle align-text-top" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <!-- View Button -->
                                                    <a class="dropdown-item" 
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#detailModal{{ $pendaftar->id }}" 
                                                       title="Lihat detail pendaftar">
                                                        View
                                                    </a>
                                            
                                                    <!-- Edit Button -->
                                                    <a class="dropdown-item" 
                                                       href="{{ route('pendaftaran.edit', $pendaftar->id) }}" 
                                                       title="Edit pendaftar">
                                                        Edit
                                                    </a>
                                            
                                                    <!-- Delete Form -->
                                                    <form action="{{ route('pendaftaran.destroy', $pendaftar->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="dropdown-item text-danger" 
                                                                title="Hapus pendaftar">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </span>
                                          </td>
                                </tr>

                                <!-- Modal Detail untuk setiap pendaftar -->
                                <div class="modal fade" id="detailModal{{ $pendaftar->id }}" 
                                     tabindex="-1" 
                                     aria-labelledby="detailModalLabel{{ $pendaftar->id }}" 
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel{{ $pendaftar->id }}">
                                                    Detail Calon Siswa
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($pendaftar->foto)
                                                    <div class="text-center mb-3">
                                                        <img src="{{ asset($pendaftar->foto) }}" 
                                                             alt="Foto Siswa" 
                                                             class="img-thumbnail" 
                                                             style="max-width: 200px;">
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Nama:</strong> {{ $pendaftar->nama }}</p>
                                                        <p><strong>NISN:</strong> {{ $pendaftar->NISN }}</p>
                                                        <p><strong>Alamat:</strong> {{ $pendaftar->alamat }}</p>
                                                        <p><strong>Tempat Lahir:</strong> {{ $pendaftar->tmp_lahir }}</p>
                                                        <p><strong>Tanggal Lahir:</strong> {{ $pendaftar->tgl_lahir }}</p>
                                                        <p><strong>Jenis Kelamin:</strong> {{ $pendaftar->jenis_kelamin }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Asal Sekolah:</strong> {{ $pendaftar->asal_sekolah }}</p>
                                                        <p><strong>Agama:</strong> {{ $pendaftar->agama }}</p>
                                                        <p><strong>Tahun Ajaran:</strong> {{ $pendaftar->tahun_ajaran_id }}</p>
                                                        <p><strong>Nama Orang Tua:</strong> {{ $pendaftar->nama_ortu }}</p>
                                                        <p><strong>Pekerjaan Orang Tua:</strong> {{ $pendaftar->pekerjaan_ortu }}</p>
                                                        <p><strong>No Telepon Orang Tua:</strong> {{ $pendaftar->no_telp_ortu }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#datatable-pendaftar').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
    </script>
    @endpush
</x-layout>
