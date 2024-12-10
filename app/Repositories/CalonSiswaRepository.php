<?php

namespace App\Repositories;

use App\Models\CalonSiswa;

class CalonSiswaRepository
{
    public function getAll()
    {
        return CalonSiswa::all();
    }

    public function create(array $data)
    {
        return CalonSiswa::create($data);
    }

    public function update(CalonSiswa $calonSiswa, array $data)
    {
        return $calonSiswa->update($data);
    }

    public function delete(CalonSiswa $calonSiswa)
    {
        return $calonSiswa->delete();
    }
}
