<?php
namespace App\Http\Controllers;

use App\Jobs\ProcessCsvBatchJob;
use App\Models\UploadLog;
use App\Services\Contracts\CsvProcessorServiceInterface;

set_time_limit(60);
ini_set('memory_limit', '4096M');

use App\Models\Boleto;
use App\Services\BoletoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class BoletoController extends Controller
{
    protected $boletoService;

    protected $csvProcessorService;

    public function __construct(CsvProcessorServiceInterface $csvProcessorService)
    {
        $this->csvProcessorService = $csvProcessorService;
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv',
        ]);

        $result = $this->csvProcessorService->processCsv($request->file('file'));

        return response()->json($result['message'], $result['status']);
    }
}
