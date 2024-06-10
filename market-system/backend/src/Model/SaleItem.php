<?php

namespace App\Model;

use App\Database\DatabaseConnection;
use PDO;

class SaleItem
{
    public $id;
    public $sale_id;
    public $product_id;
    public $quantity;
    public $price_at_time;
    public $tax_amount;
    public $created_at;
    public $deleted_at;

    public function save()
    {
        $database = DatabaseConnection::getInstance();
        if ($this->id) {            
            $statement = $database->prepare("UPDATE sales_items SET sale_id = :sale_id, product_id = :product_id, quantity = :quantity, price_at_time = :price_at_time, tax_amount = :tax_amount, created_at = :created_at WHERE id = :id");
            $statement->bindParam(':id', $this->id);
            $statement->bindParam(':sale_id', $this->sale_id);
            $statement->bindParam(':product_id', $this->product_id);
            $statement->bindParam(':quantity', $this->quantity);
            $statement->bindParam(':price_at_time', $this->price_at_time);
            $statement->bindParam(':tax_amount', $this->tax_amount);
            $statement->bindParam(':created_at', $this->created_at);
            $statement->execute();
        } else {
            $statement = $database->prepare("INSERT INTO sales_items (sale_id, product_id, quantity, price_at_time, tax_amount, created_at) VALUES (:sale_id, :product_id, :quantity, :price_at_time, :tax_amount, :created_at)");
            $statement->bindParam(':sale_id', $this->sale_id);
            $statement->bindParam(':product_id', $this->product_id);
            $statement->bindParam(':quantity', $this->quantity);
            $statement->bindParam(':price_at_time', $this->price_at_time);
            $statement->bindParam(':tax_amount', $this->tax_amount);
            $statement->bindParam(':created_at', $this->created_at);
            $statement->execute();
            $this->id = $database->lastInsertId();
        }
    }

    public static function findBySaleId($saleId)
    {      
        $statement = DatabaseConnection::getInstance()->prepare("SELECT * FROM sales_items WHERE sale_id = :sale_id");
        $statement->execute([':sale_id' => $saleId]);
        return $statement->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}
