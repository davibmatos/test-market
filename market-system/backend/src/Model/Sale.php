<?php

namespace App\Model;

use App\Database\DatabaseConnection;

class Sale
{
    public $id;
    public $sale_date;
    public $deleted_at;
    public $total;
    public $taxes;

    public function save()
    {
        $db = DatabaseConnection::getInstance();
        if ($this->id) {
        } else {
            $stmt = $db->prepare("INSERT INTO sales (sale_date) VALUES (:sale_date)");
            $stmt->bindParam(':sale_date', $this->sale_date);
            $stmt->execute();
            $this->id = $db->lastInsertId();
        }
    }

    public static function findById($id)
    {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("SELECT * FROM sales WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    public static function findAll()
    {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("
            SELECT 
                s.id, 
                s.sale_date,
                COALESCE(SUM(si.price_at_time * si.quantity), 0) AS total,
                COALESCE(SUM(si.tax_amount), 0) AS taxes
            FROM sales s
            LEFT JOIN sale_items si ON s.id = si.sale_id
            GROUP BY s.id, s.sale_date
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public function items()
    {
        return SaleItem::findBySaleId($this->id);
    }
}
