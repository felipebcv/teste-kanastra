<?php
namespace App\Http\Controllers;
set_time_limit(0);
ini_set('memory_limit', '4096M');

use App\Models\Boleto;
use App\Services\BoletoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class BoletoController extends Controller
{
    protected $boletoService;

    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }

    public function index()
    {
        $boletos = $this->boletoService->getAllBoletos();
        return response()->json($boletos);
    }

    public function show($id)
    {
        $boleto = $this->boletoService->getBoletoById($id);
        return response()->json($boleto);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'governmentId' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'dueDate' => 'required|date',
            'boletoId' => 'required|uuid',
        ]);

        $boleto = $this->boletoService->createBoleto($data);
        return response()->json($boleto, 201);
    }

    public function update(Request $request, Boleto $boleto)
    {
        $data = $request->validate([
            'name' => 'string',
            'governmentId' => 'string',
            'email' => 'email',
            'amount' => 'numeric',
            'dueDate' => 'date',
        ]);

        $updatedBoleto = $this->boletoService->updateBoleto($boleto, $data);
        return response()->json($updatedBoleto);
    }

    public function destroy(Boleto $boleto)
    {
        $this->boletoService->deleteBoleto($boleto);
        return response()->json(null, 204);
    }

    public function uploadCsv(Request $request)
    {

        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');

        try {

            if (($handle = fopen($file->getRealPath(), 'r')) === false) {
                throw new \Exception('Could not open the file.');
            }


            $header = fgetcsv($handle, 1000, ',');

            if (!$header) {
                throw new \Exception('Invalid CSV header.');
            }

            $batchData = [];
            Log::info('Inicio do while');
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($header) !== count($row)) {
                    throw new \Exception('CSV row does not match the header columns.');
                }

                $row = array_combine($header, $row);

                if ($row === false) {
                    throw new \Exception('Failed to combine header with row data.');
                }

                if (!isset($row['name'], $row['governmentId'], $row['email'], $row['debtAmount'], $row['debtDueDate'], $row['debtId'])) {
                    throw new \Exception('Missing required fields in the row: ' . json_encode($row));
                }

                $batchData[] = [
                    'name' => $row['name'],
                    'governmentId' => $row['governmentId'],
                    'email' => $row['email'],
                    'amount' => $row['debtAmount'],
                    'dueDate' => $row['debtDueDate'],
                    'boletoId' => $row['debtId'],
                ];
            }
            Log::info('fim do while');

            fclose($handle);

            if (empty($batchData)) {
                throw new \Exception('No valid data found in the CSV.');
            }

            $this->boletoService->processCsvData($batchData);

            return response()->json(['message' => 'CSV file processed successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error processing CSV upload: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing CSV: ' . $e->getMessage()], 500);
        }
    }
}
