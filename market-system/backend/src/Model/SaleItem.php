<?php

namespace App\Model;

use App\Database\DatabaseConnection;

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
        $db = DatabaseConnection::getInstance();
        if ($this->id) {
        } else {
            $stmt = $db->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price_at_time, tax_amount, created_at) VALUES (:sale_id, :product_id, :quantity, :price_at_time, :tax_amount, :created_at)");
            $stmt->bindParam(':sale_id', $this->sale_id);
            $stmt->bindParam(':product_id', $this->product_id);
            $stmt->bindParam(':quantity', $this->quantity);
            $stmt->bindParam(':price_at_time', $this->price_at_time);
            $stmt->bindParam(':tax_amount', $this->tax_amount);
            $stmt->bindParam(':created_at', $this->created_at);
            $stmt->execute();
            $this->id = $db->lastInsertId();
        }
    }


    public static function findBySaleId($saleId)
    {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("SELECT * FROM sales_items WHERE sale_id = :sale_id");
        $stmt->execute([':sale_id' => $saleId]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }
}
