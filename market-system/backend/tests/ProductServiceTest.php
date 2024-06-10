<?php

use App\Database\DatabaseConnection;
use App\Model\Product;
use App\Model\ProductType;
use App\Service\ProductService;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private $productService;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
       
    }

    public function testCreateProductTypeSuccess()
    {
        $testInput = [
            'type_name' => 'Alimentício',
            'tax_rate' => 20
        ];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetchColumn')->willReturn(0);
        $this->pdoMock->method('prepare')->willReturn($statementMock);
        $this->pdoMock->method('lastInsertId')->willReturn('1');

        DatabaseConnection::setInstance($this->pdoMock);

        $result = (new ProductService())->createProductType($testInput);

        $this->assertInstanceOf(ProductType::class, $result);
        $this->assertEquals(1, $result->id);
    }

    public function testCreateProductSuccess()
    {
        $testInput = [
            'name' => 'Samsung S23',
            'price' => 299.99,
            'type' => 1,
            'stock' => 100
        ];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetchColumn')->willReturn(0);
        $this->pdoMock->method('prepare')->willReturn($statementMock);
        $this->pdoMock->method('lastInsertId')->willReturn('1');

        DatabaseConnection::setInstance($this->pdoMock);

        $result = (new ProductService())->createProduct($testInput);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Samsung S23', $result->name);
        $this->assertEquals(299.99, $result->price);
        $this->assertEquals(1, $result->type_id);
        $this->assertEquals(100, $result->stock);
    }
}
