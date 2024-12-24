<x-layout>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Pembayaran</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('administrasi.pembayaran.store', $administrasi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Informasi Siswa -->
                            <div class="mb-3">
                                <label class="form-label">Nama Siswa</label>
                                <input type="text" class="form-control" value="{{ $administrasi->pendaftaran->nama }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jurusan</label>
                                <input type="text" class="form-control" value="{{ $administrasi->pendaftaran->jurusan->nama_jurusan }}" readonly>
                            </div>
                            <!-- Rincian Biaya -->
                            <div class="mb-3">
                                <label class="form-label">Total Biaya</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($administrasi->biaya_pendaftaran + $administrasi->biaya_ppdb + $administrasi->biaya_mpls + $administrasi->biaya_awal_tahun, 0, ',', '.') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sudah Dibayar</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($administrasi->total_bayar, 0, ',', '.') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sisa Pembayaran</label>
                                <input type="text" class="form-control" value="Rp {{ number_format(
                                    ($administrasi->biaya_pendaftaran + $administrasi->biaya_ppdb + $administrasi->biaya_mpls + $administrasi->biaya_awal_tahun) - $administrasi->total_bayar,
                                    0, ',', '.'
                                ) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Form Pembayaran -->
                            <div class="mb-3">
                                <label class="form-label">Jenis Pembayaran</label>
                                <select name="jenis_pembayaran" class="form-select" required>
                                    <option value="">Pilih Jenis Pembayaran</option>
                                    @if(!$administrasi->is_pendaftaran_lunas)
                                        <option value="pendaftaran">Pendaftaran (Rp {{ number_format($administrasi->biaya_pendaftaran, 0, ',', '.') }})</option>
                                    @endif
                                    @if(!$administrasi->is_ppdb_lunas)
                                        <option value="ppdb">PPDB (Rp {{ number_format($administrasi->biaya_ppdb, 0, ',', '.') }})</option>
                                    @endif
                                    @if(!$administrasi->is_mpls_lunas)
                                        <option value="mpls">MPLS (Rp {{ number_format($administrasi->biaya_mpls, 0, ',', '.') }})</option>
                                    @endif
                                    @if(!$administrasi->is_awal_tahun_lunas)
                                        <option value="awal_tahun">Awal Tahun (Rp {{ number_format($administrasi->biaya_awal_tahun, 0, ',', '.') }})</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Pembayaran</label>
                                <input type="number" name="jumlah_bayar" class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                       value="{{ old('jumlah_bayar') }}" required min="1">
                                @error('jumlah_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3" id="bukti-pembayaran" style="display: none;">
                                <label class="form-label">Bukti Pembayaran</label>
                                <input type="file" name="bukti_pembayaran" class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                       accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                                @error('bukti_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('administrasi.pembayaran.detail', $administrasi->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Proses Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle bukti pembayaran
            $('select[name="metode_pembayaran"]').on('change', function() {
                if ($(this).val() === 'transfer') {
                    $('#bukti-pembayaran').show();
                    $('input[name="bukti_pembayaran"]').prop('required', true);
                } else {
                    $('#bukti-pembayaran').hide();
                    $('input[name="bukti_pembayaran"]').prop('required', false);
                }
            });

            // Validasi jumlah pembayaran
            $('select[name="jenis_pembayaran"]').on('change', function() {
                const jenis = $(this).val();
                const biaya = {
                    pendaftaran: {{ $administrasi->biaya_pendaftaran }},
                    ppdb: {{ $administrasi->biaya_ppdb }},
                    mpls: {{ $administrasi->biaya_mpls }},
                    awal_tahun: {{ $administrasi->biaya_awal_tahun }}
                };

                if (jenis) {
                    $('input[name="jumlah_bayar"]').attr('max', biaya[jenis]);
                }
            });
        });
    </script>
    @endpush
</x-layout>
