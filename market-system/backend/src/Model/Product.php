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
       
        $parameters = [
            ':name' => $this->name,
            ':price' => $this->price,
            ':stock' => $this->stock,
            ':type_id' => $this->type_id
        ];

        if ($this->id) {
            $parameters[':id'] = $this->id;
        }

        $databaseConnection->prepare($sqlQuery)->execute($parameters);

        if (!$this->id) {
            $this->id = $databaseConnection->lastInsertId();
        }
    }

    public static function findById($id)
    {
        $statement = DatabaseConnection::getInstance()->prepare("SELECT * FROM products WHERE id = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $statement->fetch();
    }

    public static function deleteById($id)
    {       
        $statement = DatabaseConnection::getInstance()->prepare("DELETE FROM products WHERE id = :id");
        $statement->execute([':id' => $id]);
        return $statement->rowCount();
    }

    public static function findAll()
    {        
        $query = "
            SELECT p.id, p.name, p.price, p.stock, p.type_id, pt.tax_rate 
            FROM products p
            LEFT JOIN product_types pt ON p.type_id = pt.id";
        $statement = DatabaseConnection::getInstance()->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($searchQuery)
    {               
        $statement = DatabaseConnection::getInstance()->prepare("SELECT * FROM products WHERE LOWER(name) LIKE :query");
        $statement->execute([':query' => '%' . strtolower($searchQuery) . '%']);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
