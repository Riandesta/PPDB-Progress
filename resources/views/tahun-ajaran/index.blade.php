@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ isset($tahunAjaran) ? 'Edit' : 'Tambah' }} Tahun Ajaran</h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($tahunAjaran) ? route('tahun-ajaran.update', $tahunAjaran->id) : route('tahun-ajaran.store') }}" 
                  method="POST">
                @csrf
                @if(isset($tahunAjaran))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" 
                           name="tahun_ajaran" 
                           class="form-control @error('tahun_ajaran') is-invalid @enderror"
                           value="{{ old('tahun_ajaran', $tahunAjaran->tahun_ajaran ?? '') }}"
                           placeholder="Contoh: 2023/2024">
                    @error('tahun_ajaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               name="is_active" 
                               class="form-check-input" 
                               value="1 old('is_active', $tahunAjaran->is_active ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktifkan Tahun Ajaran Ini</label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
