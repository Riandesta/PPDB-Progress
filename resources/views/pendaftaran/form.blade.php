<x-layout>
    <x-slot name="title">
        {{ isset($calonSiswa->id) ? 'Edit Data Calon Siswa' : 'Tambah Data Calon Siswa' }}
    </x-slot>

    <form method="POST"
    action="{{ isset($calonSiswa->id) ? route('calonSiswa.update', $calonSiswa->id) : route('calonSiswa.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if(isset($calonSiswa->id))
        @method('PUT')
    @endif


        <div class="mb-3">
            <a href="{{ route('calonSiswa.show') }}">
                <button type="button" class="btn btn-secondary">Back</button>
            </a>
        </div>

        <!-- Form fields yang sudah ada -->
        <div class="mb-3">
            <label for="NISN" class="form-label">NISN</label>
            <input type="text" class="form-control @error('NISN') is-invalid @enderror" id="NISN" name="NISN"
                value="{{ old('NISN', $calonSiswa->NISN ?? '') }}" required>
            @error('NISN')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                value="{{ old('nama', $calonSiswa->nama ?? '') }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $calonSiswa->alamat ?? '') }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir"
                name="tgl_lahir" max="{{ date('Y-m-d') }}" value="{{ old('tgl_lahir', $calonSiswa->tgl_lahir ?? '') }}"
                required>
            @error('tgl_lahir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tmp_lahir" class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control @error('tmp_lahir') is-invalid @enderror" id="tmp_lahir"
                name="tmp_lahir" value="{{ old('tmp_lahir', $calonSiswa->tmp_lahir ?? '') }}" required>
            @error('tmp_lahir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="jenis_kelamin" value="L"
                    {{ old('jenis_kelamin', $calonSiswa->jenis_kelamin ?? '') == 'L' ? 'checked' : '' }} required>
                <label class="form-check-label">Laki-laki</label>
            </div>
            <div class="form-check">
                <input type="radio" class="form-check-input" name="jenis_kelamin" value="P"
                    {{ old('jenis_kelamin', $calonSiswa->jenis_kelamin ?? '') == 'P' ? 'checked' : '' }}>
                <label class="form-check-label">Perempuan</label>
            </div>
            @error('jenis_kelamin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="agama" class="form-label">Agama</label>
            <select class="form-select @error('agama') is-invalid @enderror" id="agama" name="agama" required>
                <option value="">Pilih Agama</option>
                @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                    <option value="{{ $agama }}"
                        {{ old('agama', $calonSiswa->agama ?? '') == $agama ? 'selected' : '' }}>
                        {{ $agama }}
                    </option>
                @endforeach
            </select>
            @error('agama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
            <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror" id="asal_sekolah"
                name="asal_sekolah" value="{{ old('asal_sekolah', $calonSiswa->asal_sekolah ?? '') }}" required>
            @error('asal_sekolah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

       <!-- resources/views/pendaftaran/form.blade.php -->

<div class="form-group">
    <label for="jurusan_id">Jurusan</label>
    <select name="jurusan_id" id="jurusan_id" class="form-control @error('jurusan_id') is-invalid @enderror">
        <option value="">Pilih Jurusan</option>
        @foreach($jurusans as $jurusan)
            <option value="{{ $jurusan->id }}"
                {{ old('jurusan_id', $calonSiswa->jurusan_id ?? '') == $jurusan->id ? 'selected' : '' }}>
                {{ $jurusan->nama_jurusan }}
            </option>
        @endforeach
    </select>
    @error('jurusan_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="tahun_ajaran">Tahun Ajaran</label>
    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror">
        <option value="">Pilih Tahun Ajaran</option>
        @php
            $currentYear = date('Y');
            for($i = 0; $i < 3; $i++) {
                $tahunAjaran = ($currentYear + $i) . '/' . ($currentYear + $i + 1);
        @endphp
            <option value="{{ $tahunAjaran }}"
                {{ old('tahun_ajaran', $calonSiswa->tahun_ajaran ?? '') == $tahunAjaran ? 'selected' : '' }}>
                {{ $tahunAjaran }}
            </option>
        @php
            }
        @endphp
    </select>
    @error('tahun_ajaran')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
{{--

        <div class="mb-3">
            <label for="jurusan" class="form-label">Jurusan</label>
            <select class="form-select @error('jurusan_id') is-invalid @enderror"
                    id="jurusan"
                    name="jurusan_id"
                    required>
                <option value="">Pilih Jurusan</option>
                @foreach ($jurusans as $jurusan)
                    <option value="{{ $jurusan->id }}"
                        {{ old('jurusan_id', $calonSiswa->jurusan_id ?? '') == $jurusan->id ? 'selected' : '' }}>
                        {{ $jurusan->nama_jurusan }}
                    </option>
                @endforeach
            </select>
            @error('jurusan_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div> --}}


        <!-- Data Orang Tua -->
        <div class="mb-3">
            <label for="nama_ortu" class="form-label">Nama Orang Tua</label>
            <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror" id="nama_ortu"
                name="nama_ortu" value="{{ old('nama_ortu', $calonSiswa->nama_ortu ?? '') }}" required>
            @error('nama_ortu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="pekerjaan_ortu" class="form-label">Pekerjaan Orang Tua</label>
            <input type="text" class="form-control @error('pekerjaan_ortu') is-invalid @enderror"
                id="pekerjaan_ortu" name="pekerjaan_ortu"
                value="{{ old('pekerjaan_ortu', $calonSiswa->pekerjaan_ortu ?? '') }}" required>
            @error('pekerjaan_ortu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_telp_ortu" class="form-label">Nomor Telepon Orang Tua</label>
            <input type="text" class="form-control @error('no_telp_ortu') is-invalid @enderror" id="no_telp_ortu"
                name="no_telp_ortu" value="{{ old('no_telp_ortu', $calonSiswa->no_telp_ortu ?? '') }}" required>
            @error('no_telp_ortu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            @if (isset($calonSiswa->foto))
                <div class="mb-2">
                    <img src="{{ asset($calonSiswa->foto) }}" alt="Foto calonSiswa" class="img-fluid"
                        style="max-width: 150px;">
                </div>
            @endif
            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto"
                name="foto">
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nilai Rapor</label>
            <div class="row">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="col">
                        <input type="number" step="0.01"
                            class="form-control @error('nilai_semester_' . $i) is-invalid @enderror"
                            name="nilai_semester_{{ $i }}" placeholder="Semester {{ $i }}"
                            value="{{ old('nilai_semester_' . $i, $calonSiswa->{'nilai_semester_' . $i} ?? '') }}">
                        @error('nilai_semester_' . $i)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endfor
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Status Dokumen</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="status_dokumen" value="1"
                    {{ old('status_dokumen', $calonSiswa->status_dokumen ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">Dokumen Lengkap</label>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                {{ isset($calonSiswa->id) ? 'Update' : 'Submit' }}
            </button>
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        </div>
    </form>
</x-layout>

<script>
    $(document).ready(function() {
        // Preview image before upload
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#foto").change(function() {
            readURL(this);
        });

        // Form validation
        $('form').submit(function(e) {
            let isValid = true;

            // Validate NISN
            const nisn = $('#NISN').val();
            if (!/^\d{10}$/.test(nisn)) {
                alert('NISN harus 10 digit angka');
                isValid = false;
            }

            // Validate grades
            $('input[name^="nilai_semester_"]').each(function() {
                const nilai = $(this).val();
                if (nilai && (nilai < 0 || nilai > 100)) {
                    alert('Nilai harus antara 0 dan 100');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>
