<?php
namespace App\Factory;

use App\Model\Sale;

class SaleFactory {
    public function create(): Sale {
        return new Sale();
    }
}

namespace App\Factory;

use App\Model\SaleItem;

class SaleItemFactory {
    public function create(): SaleItem {
        return new SaleItem();
    }
}
