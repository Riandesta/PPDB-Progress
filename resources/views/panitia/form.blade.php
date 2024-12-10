<x-layout>

    @isset($panitia->id)
        <x-slot name="title">Edit Data Panitia</x-slot>
    @else
        <x-slot name="title">Tambah Data Panitia</x-slot>
    @endisset

    <x-slot name="card_title"> Form Panitia</x-slot>

    <form method="POST" action="{{ route('panitia.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <a href="{{ route('panitia.index') }}">
                <button type="button" class="btn btn-secondary">Back</button>
            </a>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama"
            value="{{ $panitia->nama }}" required>
        </div>
        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan"
            value="{{ $panitia->jabatan }}" required>
        </div>
        <div class="mb-3">
            <label for="unit" class="form-label">Unit</label>
            <input type="text" class="form-control" id="unit" name="unit"
            value="{{ $panitia->unit }}" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat"
            value="{{ $panitia->alamat }}"></input>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp"
            value="{{ $panitia->no_hp }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
            value="{{ $panitia->email }}" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            @if(isset($panitia->foto))
                <div class="mb-2">
                    <img src="{{ asset($panitia->foto) }}" alt="{{ $panitia->foto }}" class="img-fluid" style="max-width: 150px;">
                </div>
            @endif
            <input type="file" class="form-control" id="foto" name="foto">
        </div>

        <div class="mt-4">
            <input type="hidden" value="{{ $panitia->id }}" name="id">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</x-layout>
