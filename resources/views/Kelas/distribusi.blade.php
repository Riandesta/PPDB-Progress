<x-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Distribusi Kelas - Tahun Ajaran {{ $tahunAjaran->tahun_ajaran }}</h5>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <form action="{{ route('kelas.proses-distribusi') }}" method="POST">
                @csrf
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Rata-rata Nilai</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $index => $s)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $s->NISN }}</td>
                                    <td>{{ $s->nama }}</td>
                                    <td>{{ $s->jurusan->nama_jurusan }}</td>
                                    <td>{{ number_format($s->rata_rata_nilai, 2) }}</td>
                                    <td>
                                        <select name="siswa_kelas[{{ $s->id }}]" class="form-select" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach($kelas[$s->jurusan_id] ?? [] as $k)
                                                <option value="{{ $k->id }}" {{ $s->kelas_id == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Distribusi</button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                pageLength: 25,
                ordering: true
            });
        });
    </script>
    @endpush
</x-layout>
