<x-layout>
    <div class="container">
        <h1>Data Pendaftaran</h1>

        <div class="mb-3">
            <a href="{{ route('calonSiswa.create') }}" class="btn btn-primary">
                + Tambah Data
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Asal Sekolah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list_calonSiswa as $calonSiswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $calonSiswa->NISN }}</td>
                        <td>{{ $calonSiswa->nama }}</td>
                        <td>{{ $calonSiswa->jenis_kelamin }}</td>
                        <td>{{ $calonSiswa->asal_sekolah }}</td>
                        <td>{{ $calonSiswa->status_seleksi }}</td>
                        <td>
                            <a href="{{ route('calonSiswa.edit', $calonSiswa->id) }}"
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('calonSiswa.destroy', $calonSiswa->id) }}"
                                  method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $list_calonSiswa->links() }}
        </div>
    </div>
</x-layout>
