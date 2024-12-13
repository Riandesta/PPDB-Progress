<x-layout>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ isset($administrasi) ? 'Update' : 'Tambah' }} Pembayaran</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ isset($administrasi) ? route('administrasi.update', $administrasi->id) : route('administrasi.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($administrasi))
                    @method('PUT')
                @endif

                <!-- Informasi Biaya -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Detail Biaya</h5>
                        <table class="table table-sm">
                            <tr>
                                <td>Biaya Pendaftaran</td>
                                <td>: Rp {{ number_format($administrasi->biaya_pendaftaran ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Biaya PPDB</td>
                                <td>: Rp {{ number_format($administrasi->biaya_ppdb ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Biaya MPLS</td>
                                <td>: Rp {{ number_format($administrasi->biaya_mpls ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Biaya Awal Tahun</td>
                                <td>: Rp {{ number_format($administrasi->biaya_awal_tahun ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-info">
                                <td><strong>Total Biaya</strong></td>
                                <td><strong>: Rp {{ number_format($administrasi->total_biaya ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr class="table-warning">
                                <td><strong>Sisa Pembayaran</strong></td>
                                <td><strong>: Rp {{ number_format($administrasi->sisa_pembayaran ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jumlah Pembayaran</label>
                            <input type="number"
                                   name="jumlah_bayar"
                                   class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                   value="{{ old('jumlah_bayar') }}"
                                   min="1"
                                   max="{{ $administrasi->sisa_pembayaran ?? 0 }}"
                                   required>
                            @error('jumlah_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal pembayaran: Rp {{ number_format($administrasi->sisa_pembayaran ?? 0, 0, ',', '.') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3" id="bukti-pembayaran-section">
                    <label class="form-label">Bukti Pembayaran</label>
                    <input type="file"
                           name="bukti_pembayaran"
                           class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                           accept="image/*">
                    @error('bukti_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Upload bukti pembayaran (max: 2MB)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan"
                              class="form-control @error('keterangan') is-invalid @enderror"
                              rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('administrasi.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($administrasi) ? 'Update' : 'Tambah' }} Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle bukti pembayaran berdasarkan metode pembayaran
            $('select[name="metode_pembayaran"]').change(function() {
                if ($(this).val() == 'transfer') {
                    $('#bukti-pembayaran-section').show();
                    $('input[name="bukti_pembayaran"]').prop('required', true);
                } else {
                    $('#bukti-pembayaran-section').hide();
                    $('input[name="bukti_pembayaran"]').prop('required', false);
                }
            });

            // Validasi jumlah pembayaran
            $('input[name="jumlah_bayar"]').on('input', function() {
                let value = parseFloat($(this).val());
                let max = parseFloat($(this).attr('max'));
                 > max) {
                    $(this).val(max);
                }
            }) ;
    </script>
    @endpush
</x-layout>
