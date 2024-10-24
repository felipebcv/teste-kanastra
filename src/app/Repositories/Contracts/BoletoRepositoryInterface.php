<?php

namespace App\Repositories\Contracts;

use App\Models\Boleto;

interface BoletoRepositoryInterface
{
    public function all();
    public function findById($id);
    public function findByBoletoId($boletoId);
    public function create(array $data);
    public function update(Boleto $boleto, array $data);
    public function delete(Boleto $boleto);
}
