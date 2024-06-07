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
        $db = DatabaseConnection::getInstance();
        $sql = $this->id
            ? "UPDATE products SET name = :name, price = :price, stock = :stock, type_id = :type_id WHERE id = :id"
            : "INSERT INTO products (name, price, stock, type_id) VALUES (:name, :price, :stock, :type_id) RETURNING id";

        $stmt = $db->prepare($sql);
        $params = [
            ':name' => $this->name,
            ':price' => $this->price,
            ':stock' => $this->stock,
            ':type_id' => $this->type_id
        ];

        if ($this->id) {
            $params[':id'] = $this->id;
        }

        $stmt->execute($params);

        if (!$this->id) {
            $this->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }
    }

    public static function findById($id)
    {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch();
    }

    public static function deleteById($id)
    {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public static function findAll()
    {
        $db = DatabaseConnection::getInstance();
        $query = "
            SELECT p.id, p.name, p.price, p.stock, p.type_id, pt.tax_rate 
            FROM products p
            LEFT JOIN product_types pt ON p.type_id = pt.id";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($query)
    {
        $db = DatabaseConnection::getInstance();
        $likeQuery = '%' . strtolower($query) . '%';
        $stmt = $db->prepare("SELECT * FROM products WHERE LOWER(name) LIKE :query");
        $stmt->execute(['query' => $likeQuery]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
