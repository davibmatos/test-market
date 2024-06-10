<?php

namespace App\Model;

use App\Database\DatabaseConnection;
use PDO;

class ProductType
{
    public $id;
    public $type_name;
    public $tax_rate;
    public $created_at;
    public $deleted_at;

    public static function findById($id)
    {
        $query = DatabaseConnection::getInstance()->prepare("SELECT * FROM product_types WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $query->fetch();
    }

    public static function deleteById($id)
    {        
        $query = DatabaseConnection::getInstance()->prepare("DELETE FROM product_types WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->rowCount();
    }

    public static function findAll()
    {
        $query = DatabaseConnection::getInstance()->prepare("SELECT * FROM product_types");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save()
    {
        $database = DatabaseConnection::getInstance();

        if ($this->id) {
            $query = $database->prepare("UPDATE product_types SET type_name = :type_name, tax_rate = :tax_rate WHERE id = :id");
            $query->execute([
                ':type_name' => $this->type_name,
                ':tax_rate' => $this->tax_rate,
                ':id' => $this->id
            ]);
            return; 
        }

        $query = $database->prepare("INSERT INTO product_types (type_name, tax_rate) VALUES (:type_name, :tax_rate)");
        $query->execute([
            ':type_name' => $this->type_name,
            ':tax_rate' => $this->tax_rate
        ]);
        $this->id = $database->lastInsertId();
    }
}
