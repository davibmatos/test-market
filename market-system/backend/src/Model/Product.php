<?php

namespace App\Model;

use App\Database\DatabaseConnection;
use PDO;

class Product
{
    public $id;
    public $name;
    public $price;
    public $stock;
    public $type_id;
    public $created_at;
    public $deleted_at;

    public function save()
    {
        $databaseConnection = DatabaseConnection::getInstance();
        $sqlQuery = $this->id
            ? "UPDATE products SET name = :name, price = :price, stock = :stock, type_id = :type_id WHERE id = :id"
            : "INSERT INTO products (name, price, stock, type_id) VALUES (:name, :price, :stock, :type_id)";

        $statement = $databaseConnection->prepare($sqlQuery);
        $parameters = [
            ':name' => $this->name,
            ':price' => $this->price,
            ':stock' => $this->stock,
            ':type_id' => $this->type_id
        ];

        if ($this->id) {
            $parameters[':id'] = $this->id;
        }

        $statement->execute($parameters);

        if (!$this->id) {
            $this->id = $databaseConnection->lastInsertId();
        }
    }

    public static function findById($id)
    {
        $databaseConnection = DatabaseConnection::getInstance();
        $statement = $databaseConnection->prepare("SELECT * FROM products WHERE id = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $statement->fetch();
    }

    public static function deleteById($id)
    {
        $databaseConnection = DatabaseConnection::getInstance();
        $statement = $databaseConnection->prepare("DELETE FROM products WHERE id = :id");
        $statement->execute([':id' => $id]);
        return $statement->rowCount();
    }

    public static function findAll()
    {
        $databaseConnection = DatabaseConnection::getInstance();
        $query = "
            SELECT p.id, p.name, p.price, p.stock, p.type_id, pt.tax_rate 
            FROM products p
            LEFT JOIN product_types pt ON p.type_id = pt.id";
        $statement = $databaseConnection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($searchQuery)
    {
        $databaseConnection = DatabaseConnection::getInstance();
        $likeQuery = '%' . strtolower($searchQuery) . '%';
        $statement = $databaseConnection->prepare("SELECT * FROM products WHERE LOWER(name) LIKE :query");
        $statement->execute([':query' => $likeQuery]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
