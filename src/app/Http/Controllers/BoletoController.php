<?php

namespace App\Http\Controllers;

use App\Services\BoletoService;
use Illuminate\Http\Request;

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

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');

            $batchData = [];
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $row = array_combine($header, $row);
                $batchData[] = [
                    'name' => $row['name'],
                    'governmentId' => $row['governmentId'],
                    'email' => $row['email'],
                    'amount' => $row['debtAmount'],
                    'dueDate' => $row['debtDueDate'],
                    'boletoId' => $row['debtId'],
                ];
            }

            fclose($handle);

            $this->boletoService->processCsvData($batchData);

            return response()->json(['message' => 'CSV file processed successfully'], 200);
        } else {
            return response()->json(['error' => 'Could not read the file'], 500);
        }
    }
}
