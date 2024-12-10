<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PanitiaController extends Controller
{
    //
    public function index()
    {
        $list_panitia = Panitia::all();
        return view('panitia.index', ['list_panitia'=>$list_panitia]);
    }
    public function create()
    {
        $objPanitia = new Panitia();

        return view('panitia.form',['panitia'=>$objPanitia]);
    }
    // :RedirectResponse
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'unit' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Simpan file gambar ke storage
            $path = $request->file('foto')->store('public/foto_panitia');
            $data['foto'] = Storage::url($path); // Menyimpan URL file ke dalam database
        } else {
        // Menghapus 'foto' dari data jika tidak ada file
        unset($data['foto']);
    }

    if (isset($request->id)) {
        $objPanitia = Panitia::find($request->id);

        if ($objPanitia) {
            $objPanitia->update($data);
            return redirect()->route('panitia.index')->with('success', 'Data berhasil diubah');
        } else {
            return redirect()->route('panitia.index')->with('error', 'Data tidak ditemukan');
        }
    } else {
        Panitia::create($data);
        return redirect()->route('panitia.index')->with('success', 'Data berhasil ditambah');
    }



    }
    public function edit($id)
    {
        $objPanitia = Panitia::find($id);
        return view('panitia.form', ['panitia'=>$objPanitia]);
    }
    public function destroy($id): RedirectResponse
    {
        $panitia = Panitia::find($id);

        if($panitia)
        {
            $panitia->delete();
            return redirect()->route('panitia.index')->with('Succes','Data berhasil dihapus');
        }
        else
        {
            return redirect()->route('panitia.index')->with('Error','Data berhasil dihapus');
        }
    }
}
