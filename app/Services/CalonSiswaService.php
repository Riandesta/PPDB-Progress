<?php

namespace App\Services;

use App\Models\CalonSiswa;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CalonSiswaRepository;

class CalonSiswaService
{
    protected $repository;

    public function __construct(CalonSiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handleUploadFoto($request)
    {
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/foto_siswa');
            return Storage::url($path);
        }
        return null;
    }

    public function createCalonSiswa($request)
    {
        $data = $request->validated();
        $data['foto'] = $this->handleUploadFoto($request);
        return $this->repository->create($data);
    }

    // In App\Services\CalonSiswaService.php

public function getAllCalonSiswa()
{
    return CalonSiswa::all();
}

}
