<?php

use App\Service\SaleService;
use App\Factory\SaleFactory;
use App\Factory\SaleItemFactory;
use App\Model\Sale;
use App\Model\SaleItem;
use PHPUnit\Framework\TestCase;

class SaleServiceTest extends TestCase
{
    private $saleService;
    private $saleFactoryMock;
    private $saleItemFactoryMock;
    private $saleMock;
    private $saleItemMock;

    protected function setUp(): void
    {
        $this->saleFactoryMock = $this->createMock(SaleFactory::class);
        $this->saleItemFactoryMock = $this->createMock(SaleItemFactory::class);

        $this->saleMock = $this->createMock(Sale::class);
        $this->saleItemMock = $this->createMock(SaleItem::class);

        $this->saleFactoryMock->method('create')->willReturn($this->saleMock);
        $this->saleItemFactoryMock->method('create')->willReturn($this->saleItemMock);

        $this->saleService = new SaleService($this->saleFactoryMock, $this->saleItemFactoryMock);

        $this->saleMock->method('save')->willReturn(true);
        $this->saleItemMock->method('save')->willReturn(true);
    }

    public function testCreateSaleSuccess()
    {
        $testInput = [
            'date' => '2023-10-01 12:00:00',
            'cart' => [
                [
                    'id' => 1,
                    'quantity' => 2,
                    'price_at_time' => 150.00,
                    'tax_amount' => 30.00
                ]
            ]
        ];
        
        $this->saleMock->id = '1';
      
        $result = $this->saleService->createSale($testInput);

        $this->assertInstanceOf(Sale::class, $result);
        $this->assertSame($this->saleMock, $result); 
        $this->assertEquals('1', $result->id);    
        $this->assertSame('1', $this->saleMock->id);
        $this->assertTrue($this->saleMock->save());
        $this->assertTrue($this->saleItemMock->save());
    }
}
