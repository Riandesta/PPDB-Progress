<x-layout>
    <x-slot name="title">
        Panitia
    </x-slot>
    <x-slot name="card_title">
        Data Panitia
    </x-slot>

    <!-- Tombol Tambah Data -->
    <a href="{{ route('panitia.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Tambah Data
    </a>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Unit</th>
                <th scope="col">Alamat</th>
                <th scope="col">Telepon</th>
                <th scope="col">Email</th>
                <th scope="col">Aksi</th> <!-- Kolom Aksi -->
            </tr>
        </thead>
        <tbody>
            @csrf
            @foreach($list_panitia as $panitia)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $panitia->nama }}</td>
                <td>{{ $panitia->unit }}</td>
                <td>{{ $panitia->alamat }}</td>
                <td>{{ $panitia->no_hp }}</td>
                <td>{{ $panitia->email }}</td>
                <td>
                    <!-- Tombol Detail -->
                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModalPanitia{{ $panitia->id }}" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <!-- Tombol Edit -->
                    <a href="{{ route('panitia.edit', $panitia->id) }}" class="btn btn-warning btn-sm" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <!-- Tombol Hapus -->
                    <form action="{{ route('panitia.destroy', $panitia->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Modal Detail -->
            <div class="modal fade" id="detailModalPanitia{{ $panitia->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $panitia->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel{{ $panitia->id }}">Detail Panitia</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <center>
                                <img src="{{ asset($panitia->foto) }}" alt="{{ $panitia->foto }}" width="100" height="100">
                            </center>
                            <p><strong>Nama:</strong> {{ $panitia->nama }}</p>
                            <p><strong>Unit:</strong> {{ $panitia->unit }}</p>
                            <p><strong>Jabatan:</strong> {{ $panitia->jabatan }}</p>
                            <p><strong>Alamat:</strong> {{ $panitia->alamat }}</p>
                            <p><strong>Telepon:</strong> {{ $panitia->no_hp }}</p>
                            <p><strong>Email:</strong> {{ $panitia->email }}</p>
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
</x-layout>
