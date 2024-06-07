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
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("SELECT * FROM product_types WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch();
    }

    public static function deleteById($id) {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("DELETE FROM product_types WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    public static function findAll() {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("SELECT * FROM product_types");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save() {
               
        $stmt = DatabaseConnection::getInstance()->prepare($this->id 
        ? "UPDATE product_types SET type_name = :type_name, tax_rate = :tax_rate WHERE id = :id"
        : "INSERT INTO product_types (type_name, tax_rate) VALUES (:type_name, :tax_rate) RETURNING id");

        $params = [
            ':type_name' => $this->type_name,
            ':tax_rate' => $this->tax_rate,
        ];
     
        if ($this->id) {
            $params[':id'] = $this->id;
        }

        $stmt->execute($params);

        if (!$this->id) {
            $this->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }
    }
}