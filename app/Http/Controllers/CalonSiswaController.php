<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\CalonSiswa;
use Illuminate\Http\Request;
use App\Services\KelasService;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CalonSiswaRequest;

class CalonSiswaController extends Controller
{
    protected $kelasService;

    public function __construct(KelasService $kelasService)
    {
        $this->kelasService = $kelasService;
    }

    public function show()
    {
        $list_calonSiswa = CalonSiswa::orderBy('created_at', 'desc')->get();
        return view('pendaftaran.calonSiswa', compact('list_calonSiswa'));


        // $list_calonSiswa = CalonSiswa::paginate(10); // 10 data per halaman

        // if (request()->ajax()) {
        //     return view('pendaftaran.table', compact('list_calonSiswa'))->render();
        // }

        // return view('pendaftaran.calonSiswa', compact('list_calonSiswa'));
    }

    public function create()
    {
        // return view('pendaftaran.form', ['calonSiswa' => new CalonSiswa()]);
        $jurusans = Jurusan::all();
        return view('pendaftaran.form', [
            'jurusans' => $jurusans,
            'calonSiswa' => null
        ]);
    }

    public function store(CalonSiswaRequest $request)
{
    try {
        $data = $request->validated();

        // Pastikan format tahun ajaran benar
        if (!preg_match('/^\d{4}\/\d{4}$/', $data['tahun_ajaran'])) {
            return back()
                ->withInput()
                ->with('error', 'Format tahun ajaran harus YYYY/YYYY');
        }

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/foto_siswa');
            $data['foto'] = Storage::url($path);
        }

        // Hitung rata-rata nilai
        $data['rata_rata_nilai'] = $this->hitungRataRata([
            $data['nilai_semester_1'] ?? 0,
            $data['nilai_semester_2'] ?? 0,
            $data['nilai_semester_3'] ?? 0,
            $data['nilai_semester_4'] ?? 0,
            $data['nilai_semester_5'] ?? 0
        ]);

        // Set status dokumen dan seleksi
        $data['status_dokumen'] = $request->has('status_dokumen');
        $data['status_seleksi'] = $this->determineSelectionStatus(
            $data['status_dokumen'],
            $data['rata_rata_nilai']
        );

        // Simpan data calon siswa
        $calonSiswa = CalonSiswa::create($data);

        // Assign ke kelas menggunakan KelasService
        if ($calonSiswa) {
            $kelasAssigned = $this->kelasService->assignSiswaToCalonSiswa($calonSiswa);

            $message = $kelasAssigned
                ? 'Pendaftaran berhasil dan siswa telah ditempatkan di kelas'
                : 'Pendaftaran berhasil tetapi penempatan kelas gagal';

            return redirect()->route('calonSiswa.show')
                ->with('success', $message);
        }

        return back()
            ->withInput()
            ->with('error', 'Gagal menyimpan data calon siswa');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function edit($id)
    {
        // $calonSiswa = CalonSiswa::findOrFail($id);
        // return view('pendaftaran.form', compact('calonSiswa'));
        $calonSiswa = CalonSiswa::findOrFail($id);
        $jurusans = Jurusan::all();
        return view('pendaftaran.form', [
            'calonSiswa' => $calonSiswa,
            'jurusans' => $jurusans
        ]);
    }

    public function update(CalonSiswaRequest $request, $id)
    {
        try {
            $calonSiswa = CalonSiswa::findOrFail($id);
            $data = $request->validated();

            // Handle foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($calonSiswa->foto) {
                    Storage::delete('public/foto_siswa/' . basename($calonSiswa->foto));
                }

                $path = $request->file('foto')->store('public/foto_siswa');
                $data['foto'] = Storage::url($path);
            }

            // Update rata-rata nilai
            $data['rata_rata_nilai'] = $this->hitungRataRata([
                $data['nilai_semester_1'] ?? 0,
                $data['nilai_semester_2'] ?? 0,
                $data['nilai_semester_3'] ?? 0,
                $data['nilai_semester_4'] ?? 0,
                $data['nilai_semester_5'] ?? 0
            ]);

            // Update status
            $data['status_dokumen'] = $request->has('status_dokumen');
            $data['status_seleksi'] = $this->determineSelectionStatus(
                $data['status_dokumen'],
                $data['rata_rata_nilai']
            );

            $calonSiswa->update($data);

            return redirect()
                ->route('calonSiswa.show')
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $calonSiswa = CalonSiswa::findOrFail($id);

            // Hapus foto jika ada
            if ($calonSiswa->foto) {
                Storage::delete('public/foto_siswa/' . basename($calonSiswa->foto));
            }

            $calonSiswa->delete();

            return redirect()
                ->route('calonSiswa.show')
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function hitungRataRata(array $nilai): float
    {
        $nilai_valid = array_filter($nilai, fn($value) => $value > 0);
        return count($nilai_valid) > 0 ? array_sum($nilai_valid) / count($nilai_valid) : 0;
    }

    private function determineSelectionStatus(bool $status_dokumen, float $rata_rata_nilai): string
    {
        if (!$status_dokumen) {
            return 'Pending';
        }
        return $rata_rata_nilai >= 75 ? 'Lulus' : 'Tidak Lulus';
    }
}
