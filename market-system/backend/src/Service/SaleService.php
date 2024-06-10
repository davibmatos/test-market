<?php

namespace App\Service;

use App\Database\DatabaseConnection;
use App\Model\Sale;
use App\Model\SaleItem;
use DateTime;

class SaleService
{
    public function createSale($data)
    {
        $database = DatabaseConnection::getInstance();
        $database->beginTransaction();

        try {
            $sale = new Sale();
            $date = new DateTime($data['date']);
            $sale->sale_date = $date->format('Y-m-d H:i:s');
            $sale->save();

            if (isset($data['cart']) && is_array($data['cart'])) {
                foreach ($data['cart'] as $item) {
                    if (isset($item['id'], $item['quantity'], $item['price'])) {
                        $saleItem = new SaleItem();
                        $saleItem->sale_id = $sale->id;
                        $saleItem->product_id = $item['id'];
                        $saleItem->quantity = $item['quantity'];
                        $saleItem->price_at_time = $item['price'];
                        $saleItem->tax_amount = $item['tax'] ?? 0;
                        $saleItem->created_at = date('Y-m-d H:i:s');
                        $saleItem->save();
                    }
                }
            }

            $database->commit();
            return $sale;
        } catch (\Exception $exception) {
            $database->rollback();
            error_log($exception->getMessage());
            throw $exception;
        }
    }

    public function getAllSales()
    {
        return Sale::findAll();
    }

    public function getSaleDetails($id)
    {
        return ['sale' => Sale::findById($id), 'items' => SaleItem::findBySaleId($id)];
    }
}
