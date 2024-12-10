<x-layout>
    <x-slot name="title">
        Pendaftaran
    </x-slot>
    <x-slot name="card_title">
        Data Pendaftaran
    </x-slot>

    <!-- Tombol Tambah Data -->
    <a href="{{ route('calonSiswa.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Tambah Data
    </a>

    <table class="table" id="table-container">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">NISN</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Asal Sekolah</th>
                <th scope="col">Jurusan</th>
                <th scope="col">Rata-rata Nilai</th>
                <th scope="col">Status Dokumen</th>
                <th scope="col">Status Seleksi</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list_calonSiswa as $calonSiswa)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $calonSiswa->nama }}</td>
                    <td>{{ $calonSiswa->NISN }}</td>
                    <td>{{ $calonSiswa->jenis_kelamin }}</td>
                    <td>{{ $calonSiswa->asal_sekolah }}</td>
                    <td>{{ $calonSiswa->jurusan->nama_jurusan }}</td>
                    <td>{{ number_format($calonSiswa->rata_rata_nilai, 2) }}</td>
                    <td>
                        @if ($calonSiswa->status_dokumen)
                            <span class="badge bg-success">Lengkap</span>
                        @else
                            <span class="badge bg-danger">Belum Lengkap</span>
                        @endif
                    </td>
                    <td>
                        @if ($calonSiswa->status_seleksi == 'Lulus')
                            <span class="badge bg-success">Lulus</span>
                        @elseif($calonSiswa->status_seleksi == 'Tidak Lulus')
                            <span class="badge bg-danger">Tidak Lulus</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        <!-- Tombol Detail -->
                        <button class="btn btn-info btn-sm" data-toggle="modal"
                            data-target="#detailModal{{ $calonSiswa->id }}" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>

                        <!-- Tombol Edit -->
                        <a href="{{ route('calonSiswa.edit', $calonSiswa->id) }}" class="btn btn-warning btn-sm"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('calonSiswa.destroy', $calonSiswa->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        @if($calonSiswa->status_seleksi == 'Lulus')
                            @if($calonSiswa->kelas)
                                <span class="badge bg-success">{{ $calonSiswa->kelas->nama_kelas }}</span>
                            @else
                                <span class="badge bg-warning">Belum ada kelas</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>

                </tr>

                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal{{ $calonSiswa->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="detailModalLabel{{ $calonSiswa->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel{{ $calonSiswa->id }}">Detail Calon Siswa
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if ($calonSiswa->foto)
                                    <center>
                                        <img src="{{ asset($calonSiswa->foto) }}" alt="Foto Siswa" width="100"
                                            height="100">
                                    </center>
                                @endif
                                <p><strong>Nama:</strong> {{ $calonSiswa->nama }}</p>
                                <p><strong>NISN:</strong> {{ $calonSiswa->NISN }}</p>
                                <p><strong>Alamat:</strong> {{ $calonSiswa->alamat }}</p>
                                <p><strong>Tempat Lahir:</strong> {{ $calonSiswa->tmp_lahir }}</p>
                                <p><strong>Tanggal Lahir:</strong> {{ $calonSiswa->tgl_lahir }}</p>
                                <p><strong>Jenis Kelamin:</strong> {{ $calonSiswa->jenis_kelamin }}</p>
                                <p><strong>Asal Sekolah:</strong> {{ $calonSiswa->asal_sekolah }}</p>
                                <p><strong>Agama:</strong> {{ $calonSiswa->agama }}</p>
                                <p><strong>Tahun Ajaran:</strong> {{ $calonSiswa->tahun_ajaran }}</p>
                                <p><strong>Nama Orang Tua:</strong> {{ $calonSiswa->nama_ortu }}</p>
                                <p><strong>Pekerjaan Orang Tua:</strong> {{ $calonSiswa->pekerjaan_ortu }}</p>
                                <p><strong>No Telepon Orang Tua:</strong> {{ $calonSiswa->no_telp_ortu }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Fungsi untuk refresh tabel
        function refreshTable() {
            $.get('{{ route("calonSiswa.show") }}', function(data) {
                $('#table-container').html(data);
            });
        }

        // Refresh setiap 30 detik
        setInterval(refreshTable, 30000);
    </script>
</x-layout>
