<x-layout>
    <x-slot name="header">Detail Kelas</x-slot>
    <x-slot name="card_title">
        Kelas {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan }}
    </x-slot>

    <div class="mb-3">
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Informasi Kelas</h5>
            <dl class="row">
                <dt class="col-sm-3">Tahun Ajaran</dt>
                <dd class="col-sm-9">{{ $kelas->tahun_ajaran }}</dd>

                <dt class="col-sm-3">Jumlah Siswa</dt>
                <dd class="col-sm-9">{{ $kelas->calonSiswa->count() }} / {{ $kelas->jurusan->kapasitas_per_kelas }}</dd>

            </dl>
        </div>
    </div>



    <div class="card">
        <div class="card-body">
            <h5>Daftar Siswa</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas->calonSiswa->sortBy('nama') as $index => $siswa)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->NISN }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->jenis_kelamin }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $siswa->status_seleksi === 'Lulus' ? 'success' : 'warning' }}">
                                        {{ $siswa->status_seleksi }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body">
                <h5>Distribusi Siswa per Huruf</h5>
                <ul>
                    @foreach ($kelas->getLetterDistribution() as $letter => $count)
                        <li>Huruf {{ $letter }}: {{ $count }} siswa</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-layout>
