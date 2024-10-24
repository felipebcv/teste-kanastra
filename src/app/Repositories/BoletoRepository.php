<?php

namespace App\Repositories;

use App\Models\Boleto;
use App\Repositories\Contracts\BoletoRepositoryInterface;

class BoletoRepository implements BoletoRepositoryInterface
{
    public function all()
    {
        return Boleto::all();
    }

    public function findById($id)
    {
        return Boleto::findOrFail($id);
    }

    public function findByBoletoId($boletoId)
    {
        return Boleto::where('boletoId', $boletoId)->first();
    }

    public function create(array $data)
    {
        return Boleto::create($data);
    }

    public function update(Boleto $boleto, array $data)
    {
        $boleto->update($data);
        return $boleto;
    }

    public function delete(Boleto $boleto)
    {
        return $boleto->delete();
    }
}
