<x-layout>
    <x-slot name="header">Manajemen Kelas</x-slot>

    <div class="container">
        <div class="row">
            @foreach ($kelasGroup as $jurusanNama => $kelasCollection)
                <div class="col-12 mb-4">
                    <h4>{{ $jurusanNama }}</h4>
                    <div class="row">
                        @foreach ($kelasCollection as $kelas)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $kelas->nama_kelas }}</h5>
                                        <p class="card-text">
                                            Tahun Ajaran: {{ $kelas->tahun_ajaran }}<br>
                                            Kapasitas: {{ $kelas->kapasitas_saat_ini }} / {{ $kelas->jurusan->kapasitas_per_kelas }}
                                        </p>
                                        <a href="{{ route('kelas.show', $kelas->id) }}"
                                           class="btn btn-info btn-sm">
                                            <i class="fa fa-eye me-1"></i>Lihat Siswa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>