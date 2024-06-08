<?php

namespace App\Model;

use App\Database\DatabaseConnection;
use PDO;

class ProductType {
    public $id;
    public $type_name;
    public $tax_rate;
    public $created_at;
    public $deleted_at;

    public static function findById($id) {
        $database = DatabaseConnection::getInstance();
        $query = $database->prepare("SELECT * FROM product_types WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $query->fetch();
    }

    public static function deleteById($id) {
        $database = DatabaseConnection::getInstance();
        $query = $database->prepare("DELETE FROM product_types WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->rowCount();
    }

    public static function findAll() {
        $database = DatabaseConnection::getInstance();
        $query = $database->prepare("SELECT * FROM product_types");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save() {
        $database = DatabaseConnection::getInstance();
        $query = $database->prepare($this->id
        ? "UPDATE product_types SET type_name = :type_name, tax_rate = :tax_rate WHERE id = :id"
        : "INSERT INTO product_types (type_name, tax_rate) VALUES (:type_name, :tax_rate) RETURNING id");

        $parameters = [
            ':type_name' => $this->type_name,
            ':tax_rate' => $this->tax_rate,
        ];

        if ($this->id) {
            $parameters[':id'] = $this->id;
        }

        $query->execute($parameters);

        if (!$this->id) {
            $this->id = $query->fetch(PDO::FETCH_ASSOC)['id'];
        }
    }
}
