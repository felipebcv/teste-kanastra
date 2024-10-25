<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessCsvBatchJob;
use App\Models\UploadLog;
use App\Services\Contracts\CsvProcessorServiceInterface;
use Exception;

class CsvProcessorService implements CsvProcessorServiceInterface
{
    public function processCsv($file)
    {
        $batchSize = 12000;
        $totalLinesInFile = $this->countLines($file);
        $retries = 5;
        $isSuccessful = false;

        for ($retry = 1; $retry <= $retries && !$isSuccessful; $retry++) {
            try {
                $totalProcessed = $this->readAndDispatchBatches($file, $batchSize, $totalLinesInFile);
                $isSuccessful = $totalProcessed === $totalLinesInFile;
            } catch (Exception $e) {
                Log::error("CSV processing error: " . $e->getMessage());
            }
        }

        $status = $isSuccessful ? 'Y' : 'N';
        $this->logUploadResult($file->getClientOriginalName(), $totalLinesInFile, $retry - 1, $status);

        return [
            'status' => $isSuccessful ? 200 : 500,
            'message' => $isSuccessful
                ? ['message' => 'CSV file processed successfully.']
                : ['error' => 'CSV file processing failed.'],
        ];
    }

    public function countLines($file)
    {
        $lineCount = 0;
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (fgetcsv($handle) !== false) {
                $lineCount++;
            }
            fclose($handle);
        }
        return max(0, $lineCount - 1);
    }

    public function readAndDispatchBatches($file, $batchSize, $totalLines)
    {
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');
        $batchData = [];
        $processedLines = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $row = array_combine($header, $row);
            $batchData[] = $this->validateRow($row);
            $processedLines++;

            if (count($batchData) === $batchSize) {
                ProcessCsvBatchJob::dispatch($batchData);
                $batchData = [];
            }
        }

        if (!empty($batchData)) {
            ProcessCsvBatchJob::dispatch($batchData);
        }

        fclose($handle);
        return $processedLines;
    }

    public function validateRow($row)
    {
        if (!isset($row['name'], $row['governmentId'], $row['email'], $row['debtAmount'], $row['debtDueDate'], $row['debtId'])) {
            throw new Exception('Missing required fields in row.');
        }

        return [
            'name' => $row['name'],
            'governmentId' => $row['governmentId'],
            'email' => $row['email'],
            'amount' => $row['debtAmount'],
            'dueDate' => $row['debtDueDate'],
            'boletoId' => $row['debtId'],
        ];
    }

    public function logUploadResult($fileName, $totalLines, $retry, $status)
    {
        UploadLog::create([
            'file_name' => $fileName,
            'total_lines' => $totalLines,
            'retry' => $retry,
            'status' => $status,
        ]);
    }
}
