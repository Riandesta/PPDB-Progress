<x-layout>
    <x-slot name="title">
        Detail Pendaftaran
    </x-slot>

    <div class="container">
        <div class="row">
            <!-- Data Calon Siswa -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Data Calon Siswa</h4>
                    </div>
                    <div class="card-body">
                        @if($calonSiswa->foto)
                            <div class="text-center mb-4">
                                <img src="{{ asset($calonSiswa->foto) }}"
                                     alt="Foto {{ $calonSiswa->nama }}"
                                     class="img-fluid rounded"
                                     style="max-height: 200px;">
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Data Pribadi</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="150">NISN</td>
                                        <td>: {{ $calonSiswa->NISN }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Lengkap</td>
                                        <td>: {{ $calonSiswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>TTL</td>
                                        <td>: {{ $calonSiswa->tmp_lahir }}, {{ $calonSiswa->tgl_lahir }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td>: {{ $calonSiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Agama</td>
                                        <td>: {{ $calonSiswa->agama }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>: {{ $calonSiswa->alamat }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Data Akademik</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="150">Asal Sekolah</td>
                                        <td>: {{ $calonSiswa->asal_sekolah }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jurusan</td>
                                        <td>: {{ $calonSiswa->jurusan->nama_jurusan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Seleksi</td>
                                        <td>: <span class="badge bg-{{ $calonSiswa->status_seleksi == 'Lulus' ? 'success' : ($calonSiswa->status_seleksi == 'Pending' ? 'warning' : 'danger') }}">
                                            {{ $calonSiswa->status_seleksi }}
                                        </span></td>
                                    </tr>
                                    @if($calonSiswa->kelas)
                                    <tr>
                                        <td>Kelas</td>
                                        <td>: {{ $calonSiswa->kelas->nama_kelas }}</td>
                                    </tr>
                                    @endif
                                </table>

                                <h6>Nilai Akademik</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Semester 1</td>
                                        <td>: {{ $calonSiswa->nilai_semester_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Semester 2</td>
                                        <td>: {{ $calonSiswa->nilai_semester_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Semester 3</td>
                                        <td>: {{ $calonSiswa->nilai_semester_3 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Semester 4</td>
                                        <td>: {{ $calonSiswa->nilai_semester_4 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Semester 5</td>
                                        <td>: {{ $calonSiswa->nilai_semester_5 }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Rata-rata</strong></td>
                                        <td><strong>: {{ number_format($calonSiswa->rata_rata_nilai, 2) }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <h5>Data Orang Tua</h5>
                        <table class="table table-sm">
                            <tr>
                                <td width="200">Nama Orang Tua</td>
                                <td>: {{ $calonSiswa->nama_ortu }}</td>
                            </tr>
                            <tr>
                                <td>Pekerjaan Orang Tua</td>
                                <td>: {{ $calonSiswa->pekerjaan_ortu }}</td>
                            </tr>
                            <tr>
                                <td>No. Telepon Orang Tua</td>
                                <td>: {{ $calonSiswa->no_telp_ortu }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Data Administrasi -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        @if($calonSiswa->administrasi)
                            <div class="mb-3">
                                <h5>Status:
                                    <span class="badge bg-{{ $calonSiswa->administrasi->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                        {{ $calonSiswa->administrasi->status_pembayaran }}
                                    </span>
                                </h5>
                            </div>

                            <table class="table table-sm">
                                <tr>
                                    <td>Biaya Pendaftaran</td>
                                    <td class="text-end">Rp {{ number_format($calonSiswa->administrasi->biaya_pendaftaran, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya PPDB</td>
                                    <td class="text-end">Rp {{ number_format($calonSiswa->administrasi->biaya_ppdb, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Total Biaya</td>
                                    <td class="text-end">Rp {{ number_format($calonSiswa->administrasi->total_biaya, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Total Bayar</td>
                                    <td class="text-end">Rp {{ number_format($calonSiswa->administrasi->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sisa Pembayaran</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($calonSiswa->administrasi->sisa_pembayaran, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>

                            @if($calonSiswa->administrasi->status_pembayaran !== 'Lunas')
                                <div class="mt-3">
                                    <a href="{{ route('administrasi.show', $calonSiswa->administrasi->id) }}"
                                       class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        Lakukan Pembayaran
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                Data administrasi belum tersedia
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('pendaftaran.edit', $calonSiswa->id) }}"
                       class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Edit Data
                    </a>
                    <a href="{{ route('pendaftaran.show') }}"
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
