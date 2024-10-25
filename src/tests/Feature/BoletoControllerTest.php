<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessCsvBatchJob;

class BoletoControllerTest extends TestCase
{
    public function test_successful_csv_upload_and_processing()
    {
        Queue::fake();

        $csvContent = <<<CSV
        name,governmentId,email,debtAmount,debtDueDate,debtId
        John Doe,12345678901,john@example.com,100.50,2024-11-10,ABC123
        Jane Doe,98765432101,jane@example.com,200.75,2024-12-15,DEF456
        CSV;

        $csvFile = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        $response = $this->postJson('/api/boletos/upload', [
            'file' => $csvFile,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'CSV file processed successfully.',
                 ]);

        Queue::assertPushed(ProcessCsvBatchJob::class, function ($job) {
            return is_array($job->getBatchData()) &&
                   count($job->getBatchData()) === 2 &&
                   $job->getBatchData()[0]['name'] === 'John Doe';
        });
    }

    public function test_upload_csv_with_missing_columns()
    {
        Queue::fake();

        $csvContent = <<<CSV
        name,governmentId,email,debtAmount,debtDueDate
        John Doe,12345678901,john@example.com,100.50,2024-11-10
        CSV;

        $csvFile = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        $response = $this->postJson('/api/boletos/upload', [
            'file' => $csvFile,
        ]);

        $response->assertStatus(500)
                 ->assertJson([
                     'error' => 'CSV file processing failed.',
                 ]);

        Queue::assertNothingPushed();
    }

    public function test_upload_without_file()
    {
        $response = $this->postJson('/api/boletos/upload');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['file']);
    }

    public function test_upload_invalid_file_type()
    {
        $txtFile = UploadedFile::fake()->create('test.txt', 100); 

        $response = $this->postJson('/api/boletos/upload', [
            'file' => $txtFile,
        ]);

        $response->assertStatus(422) 
                 ->assertJsonValidationErrors(['file']);
    }

    public function test_large_csv_batch_processing()
    {
        Queue::fake();

        $csvContent = "name,governmentId,email,debtAmount,debtDueDate,debtId\n";
        for ($i = 0; $i < 12000; $i++) {
            $csvContent .= "Person $i,1234567890$i,person$i@example.com,100.00,2024-12-31,ID$i\n";
        }

        $csvFile = UploadedFile::fake()->createWithContent('large_test.csv', $csvContent);

        $response = $this->postJson('/api/boletos/upload', [
            'file' => $csvFile,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'CSV file processed successfully.',
                 ]);

        Queue::assertPushed(ProcessCsvBatchJob::class, function ($job) {
            return count($job->getBatchData()) === 12000;
        });
    }
}
