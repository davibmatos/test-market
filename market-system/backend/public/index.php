<?php

header('Access-Control-Allow-Origin: http://localhost:8081');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {   
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

$request = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = parse_url($request, PHP_URL_PATH);

switch ($requestPath) {
    case '/product/add':
        (new \App\Controller\ProductController())->addProduct();
        break;

    case '/product/edit':
        $productId = $_GET['id'] ?? null;
        (new \App\Controller\ProductController())->editProduct($productId);
        break;

    case '/product/delete':
        $productId = $_GET['id'] ?? null;
        (new \App\Controller\ProductController())->deleteProduct($productId);
        break;

    case '/product/view':
        (new \App\Controller\ProductController())->viewProducts();
        break;

    case '/product-type/add':
        if ($requestMethod == 'POST') {
            (new \App\Controller\ProductController())->addProductType();
        }
        break;

    case '/product-type/edit':
        $typeId = $_GET['id'] ?? null;
        if ($requestMethod == 'PUT' || $requestMethod == 'PATCH') {
            (new \App\Controller\ProductController())->editProductType($typeId);
        }
        break;

    case '/product-type/delete':
        $typeId = $_GET['id'] ?? null;
        if ($requestMethod == 'DELETE') {
            (new \App\Controller\ProductController())->deleteProductType($typeId);
        }
        break;

    case '/product-type/view':
        if ($requestMethod == 'GET') {
            (new \App\Controller\ProductController())->viewProductTypes();
        }
        break;

    case '/product/search':
        $query = $_GET['query'] ?? null;
        if ($requestMethod == 'GET' && $query !== null) {
            (new \App\Controller\ProductController())->searchProducts($query);
        }
        break;

    case '/sale/add':
        if ($requestMethod == 'POST') {
            (new \App\Controller\SaleController())->addSale();
        }
        break;

    case '/sale/view':
        if ($requestMethod == 'GET') {
            (new \App\Controller\SaleController())->viewSales();
        }
        break;

    case '/sale/details':
        $saleId = $_GET['id'] ?? null;
        if ($requestMethod == 'GET' && $saleId) {
            (new \App\Controller\SaleController())->viewSaleDetails($saleId);
        }
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        break;
}
