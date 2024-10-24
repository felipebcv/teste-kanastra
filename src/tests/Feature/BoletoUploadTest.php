<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Boleto;

class BoletoUploadTest extends TestCase
{
    public function testUploadCsvCreatesBoletos()
    {

        Storage::fake('local');

        $csvContent = <<<CSV
name,governmentId,email,debtAmount,debtDueDate,debtId
John Doe,11111111111,johndoe@example.com,1000.00,2022-10-12,1adb6ccf-ff16-467f-bea7-5f05d494280f
Jane Smith,22222222222,janesmith@example.com,2000.00,2022-11-15,2bdb6ccf-ff16-467f-bea7-5f05d494280f
CSV;

        $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        $response = $this->postJson('/api/boletos/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Arquivo CSV processado com sucesso']);

        $this->assertDatabaseHas('boletos', [
            'email' => 'johndoe@example.com',
            'amount' => 1000.00,
        ]);

        $this->assertDatabaseHas('boletos', [
            'email' => 'janesmith@example.com',
            'amount' => 2000.00,
        ]);
    }

}
