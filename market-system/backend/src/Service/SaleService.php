<?php

namespace App\Service;

use App\Database\DatabaseConnection;
use App\Model\Sale;
use App\Model\SaleItem;

class SaleService
{
    public function createSale($data)
    {
        $db = DatabaseConnection::getInstance();
        $db->beginTransaction();

        try {
            $sale = new Sale();
            $sale->sale_date = $data['date'] ?? date('Y-m-d H:i:s');
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

            $db->commit();
            return $sale;
        } catch (\Exception $e) {
            $db->rollback();
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function getAllSales()
    {
        return Sale::findAll();
    }

    public function getSaleDetails($id)
    {
        $sale = Sale::findById($id);
        $items = SaleItem::findBySaleId($id);
        return ['sale' => $sale, 'items' => $items];
    }
}
function dd($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    die();
}
