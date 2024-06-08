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

$productController = new \App\Controller\ProductController();
$productTypeController = new \App\Controller\ProductTypeController();
$saleController = new \App\Controller\saleController();

switch ($requestPath) {
    case '/product/add':
        if ($requestMethod == 'POST') {
            $productController->store();
        }
        break;

    case '/product/edit':
        if ($requestMethod == 'PUT' || $requestMethod == 'PATCH') {
            $productId = $_GET['id'];
            $productController->edit($productId);
        }
        break;

    case '/product/delete':
        if ($requestMethod == 'DELETE') {
            $productId = $_GET['id'];
            $productController->delete($productId);
        }
        break;

    case '/product/view':
        if ($requestMethod == 'GET') {
            $productController->index();
        }
        break;

    case '/product-type/add':
        if ($requestMethod == 'POST') {
            $productTypeController->store();
        }
        break;

    case '/product-type/edit':
        if ($requestMethod == 'PUT' || $requestMethod == 'PATCH') {
            $typeId = $_GET['id'];
            $productTypeController->edit($typeId);
        }
        break;

    case '/product-type/delete':
        if ($requestMethod == 'DELETE') {
            $typeId = $_GET['id'];
            $productTypeController->delete($typeId);
        }
        break;

    case '/product-type/view':
        if ($requestMethod == 'GET') {
            $productTypeController->index();
        }
        break;

    case '/product/search':
        if ($requestMethod == 'GET') {
            $productController->search();
        }
        break;

    case '/sale/add':
        if ($requestMethod == 'POST') {
            $saleController->store();
        }
        break;

    case '/sale/view':
        if ($requestMethod == 'GET') {
            $saleController->index();
        }
        break;

    case '/sale/details':
        if ($requestMethod == 'GET') {
            $saleId = $_GET['id'];
            $saleController->viewDetails($saleId);
        }
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        break;
}
