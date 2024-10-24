<?php

namespace App\Services;

use App\Repositories\Contracts\BoletoRepositoryInterface;
use App\Models\Boleto;
use Illuminate\Support\Facades\Log;

class BoletoService
{
    protected $boletoRepository;

    public function __construct(BoletoRepositoryInterface $boletoRepository)
    {
        $this->boletoRepository = $boletoRepository;
    }

    public function getAllBoletos()
    {
        return $this->boletoRepository->all();
    }

    public function getBoletoById($id)
    {
        return $this->boletoRepository->findById($id);
    }

    public function createBoleto(array $data)
    {
        $existingBoleto = $this->boletoRepository->findByBoletoId($data['boletoId']);
        if ($existingBoleto) {            
            return $existingBoleto;
        }
        
        $boleto = $this->boletoRepository->create($data);

        $this->generateBoleto($boleto);

        $this->sendEmail($boleto);

        return $boleto;
    }

    protected function generateBoleto(Boleto $boleto)
    {
        Log::info("Boleto generated for {$boleto->name} - BoletoID: {$boleto->boletoId}");
    }

    protected function sendEmail(Boleto $boleto)
    {
        Log::info("Email sent to {$boleto->email} with boleto {$boleto->boletoId}");
    }

    public function updateBoleto(Boleto $boleto, array $data)
    {
        return $this->boletoRepository->update($boleto, $data);
    }

    public function deleteBoleto(Boleto $boleto)
    {
        return $this->boletoRepository->delete($boleto);
    }

    public function processCsvData(array $csvData)
    {
        foreach ($csvData as $row) {
            try {
                $this->createBoleto($row);
            } catch (\Exception $e) {
                Log::error("Error processing boleto (BoletoID: {$row['boletoId']}, Name: {$row['name']}): " . $e->getMessage());
            }
        }
    }
}
