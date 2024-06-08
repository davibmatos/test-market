<?php

namespace App\Model;

use App\Database\DatabaseConnection;

class Sale
{
    public $id;
    public $saleDate;
    public $deletedAt;
    public $total;
    public $taxes;
    public $sale_date;

    public function save()
    {
        $database = DatabaseConnection::getInstance();
        $statement = $database->prepare("INSERT INTO sales (sale_date) VALUES (:sale_date)");
        $statement->bindParam(':sale_date', $this->sale_date);
        $statement->execute();
        $this->id = $database->lastInsertId();
    }

    public static function findById($id)
    {
        $database = DatabaseConnection::getInstance();
        $statement = $database->prepare("SELECT * FROM sales WHERE id = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, self::class);
        return $statement->fetch();
    }

    public static function findAll()
    {
        $database = DatabaseConnection::getInstance();
        $query = "
            SELECT 
                s.id, 
                s.sale_date,
                COALESCE(SUM(si.price_at_time * si.quantity), 0) AS total,
                COALESCE(SUM(si.tax_amount), 0) AS taxes
            FROM sales s
            LEFT JOIN sales_items si ON s.id = si.sale_id
            GROUP BY s.id, s.sale_date
        ";
        $statement = $database->prepare($query);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public function items()
    {
        return SaleItem::findBySaleId($this->id);
    }
}
