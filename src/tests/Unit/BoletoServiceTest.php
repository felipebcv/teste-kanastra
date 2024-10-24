<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BoletoService;
use App\Repositories\Contracts\BoletoRepositoryInterface;
use App\Models\Boleto;
use Mockery;
use Illuminate\Support\Facades\Log;

class BoletoServiceTest extends TestCase
{
    public function testCreateBoleto()
    {
        $data = [
            'name' => 'John Doe',
            'governmentId' => '11111111111',
            'email' => 'johndoe@example.com',
            'amount' => 1000.00,
            'dueDate' => '2022-10-12',
            'boletoId' => '1adb6ccf-ff16-467f-bea7-5f05d494280f',
        ];

        $boleto = new Boleto($data);

        $boletoRepoMock = Mockery::mock(BoletoRepositoryInterface::class);
        $boletoRepoMock->shouldReceive('findByBoletoId')->once()->andReturn(null);
        $boletoRepoMock->shouldReceive('create')->once()->andReturn($boleto);

        $boletoService = new BoletoService($boletoRepoMock);

        Log::shouldReceive('info')->twice();

        $result = $boletoService->createBoleto($data);

        $this->assertInstanceOf(Boleto::class, $result);
        $this->assertEquals($data['name'], $result->name);
    }
}
