<?php

namespace App\Jobs;

use App\Services\Contracts\BoletoServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ProcessCsvBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batchData;
    /**
     * Create a new job instance.
     */
    public function __construct(array $batchData)
    {
        $this->batchData = $batchData;
    }

    public function getBatchData() 
    {
        return $this->batchData;
    }

    /**
     * Execute the job.
     */
    public function handle(BoletoServiceInterface $boletoService): void
    {
        try {
            $chunkSize = 10000;
            $chunks = array_chunk($this->batchData, $chunkSize);

            foreach ($chunks as $chunk) {                
                $boletoService->createBoleto($chunk);               
            }

        } catch (\Exception $e) {
            Log::error('Erro ao processar o lote: ' . $e->getMessage());
        }
    }
}
