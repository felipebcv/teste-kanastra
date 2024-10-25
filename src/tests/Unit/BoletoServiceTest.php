<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BoletoService;
use App\Repositories\Contracts\BoletoRepositoryInterface;
use Mockery;
use Illuminate\Validation\ValidationException;

class BoletoServiceTest extends TestCase
{
    protected $boletoData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->boletoData = [
            'name' => 'Teste Nome',
            'governmentId' => '12345678901',
            'email' => 'teste@example.com',
            'amount' => 100.50,
            'dueDate' => '2024-11-10',
            'boletoId' => 'ABC123',
        ];
    }

    public function test_create_boleto_success()
    {
        $boletoRepoMock = Mockery::mock(BoletoRepositoryInterface::class);
        $boletoRepoMock->shouldReceive('create')
            ->once()
            ->with($this->boletoData)
            ->andReturn(true);

        $boletoService = new BoletoService($boletoRepoMock);
        $result = $boletoService->createBoleto($this->boletoData);
        $this->assertTrue($result);
    }

    public function test_create_boleto_failure()
    {
        $boletoRepoMock = Mockery::mock(BoletoRepositoryInterface::class);
        $boletoRepoMock->shouldReceive('create')
            ->once()
            ->with($this->boletoData)
            ->andReturn(false);

        $boletoService = new BoletoService($boletoRepoMock);
        $result = $boletoService->createBoleto($this->boletoData);
        $this->assertFalse($result);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
