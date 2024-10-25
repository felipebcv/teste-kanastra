<?php

namespace App\Repositories;

use App\Models\Boleto;
use App\Repositories\Contracts\BoletoRepositoryInterface;

class BoletoRepository implements BoletoRepositoryInterface
{
    public function create(array $data)
    {
        return Boleto::insertOrIgnore($data);
    }

}
