<?php

namespace App\Services;

use App\Repositories\Contracts\BoletoRepositoryInterface;
use App\Services\Contracts\BoletoServiceInterface;

class BoletoService implements BoletoServiceInterface
{
    protected $boletoRepository;

    public function __construct(BoletoRepositoryInterface $boletoRepository)
    {
        $this->boletoRepository = $boletoRepository;
    }

    public function createBoleto(array $data)
    {
        return $this->boletoRepository->create($data);
    }

}
