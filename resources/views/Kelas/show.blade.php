// views/kelas/show.blade.php
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Detail Kelas {{ $kelas->nama_kelas }}</h3>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Jurusan</dt>
            <dd class="col-sm-9">{{ $kelas->jurusan->nama_jurusan }}</dd>
            
            <dt class="col-sm-3">Tahun Ajaran</dt>
            <dd class="col-sm-9">{{ $kelas->tahun_ajaran }}</dd>
            
            <dt class="col-sm-3">Kapasitas</dt>
            <dd class="col-sm-9">{{ $kelas->kapasitas_saat_ini }} / {{ $kelas->jurusan->kapasitas_per_kelas }}</dd>
        </dl>

        <h4>Daftar Siswa</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelas->pendaftaran as $index => $siswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $siswa->NISN }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>{{ $siswa->administrasi->status_pembayaran }}</td>
                    <td>
                        <a href="{{ route('pendaftaran.show', $siswa) }}" class="btn btn-sm btn-info">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
