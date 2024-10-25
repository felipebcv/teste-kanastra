<?php

namespace App\Services\Contracts;

interface CsvProcessorServiceInterface
{
    public function processCsv($file);
    public function countLines($file);
    public function readAndDispatchBatches($file, $batchSize, $totalLines);
    public function validateRow($row);
    public function logUploadResult($fileName, $totalLines, $retry, $status);
}
