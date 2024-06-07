<?php

namespace App\Service;

use App\Database\DatabaseConnection;
use App\Model\Product;
use App\Model\ProductType;

class ProductService
{
    public function createProduct($data)
    {
        $product = new Product();
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->type_id = $data['type'];
        $product->stock = $data['stock'];
        $product->save();
        return $product;
    }

    public function createProductType($data)
    {
        $productType = new ProductType();
        $productType->type_name = $data['type_name'];
        $productType->tax_rate = $data['tax_rate'];
        $productType->save();
        return $productType;
    }

    public function updateProduct($id, $data)
    {
        $product = Product::findById($id);
        if (!$product) {
            throw new \Exception("Produto não encontrado.");
        }

        $product->name = $data['name'] ?? $product->name;
        $product->price = $data['price'] ?? $product->price;
        $product->stock = $data['stock'] ?? $product->stock;
        $product->type_id = $data['type_id'] ?? $product->type_id;

        $product->save();
        return $product;
    }

    public function validateTypeId($typeId)
    {
        $db = DatabaseConnection::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) FROM product_types WHERE id = :typeId");
        $stmt->execute([':typeId' => $typeId]);
        return $stmt->fetchColumn() > 0;
    }

    public function deleteProduct($id)
    {
        Product::deleteById($id);
        return true;
    }

    public function updateProductType($id, $data)
    {
        $productType = ProductType::findById($id);
        if ($productType) {
            $productType->type_name = $data['type_name'] ?? $productType->type_name;
            $productType->tax_rate = $data['tax_rate'] ?? $productType->tax_rate;
            $productType->save();
        }
        return $productType;
    }

    public function deleteProductType($id)
    {
        ProductType::deleteById($id);
        return true;
    }
}
