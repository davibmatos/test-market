<?php

namespace App\Controller;

use App\Model\Product;
use App\Service\ProductService;

class ProductController extends BaseController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index()
    {
        $products = Product::findAll();
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function store()
    {
        try {
            $product = $this->productService->createProduct(json_decode(file_get_contents('php://input'), true));
            $this->successResponse($product, "Produto criado com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        try {
            $this->productService->updateProduct($id, json_decode(file_get_contents('php://input'), true));
            $this->successResponse(null, "Produto atualizado com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->productService->deleteProduct($id);
            $this->successResponse(null, "Produto excluído com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function search()
    {
        $query = $_GET['query'] ?? '';

        if (empty($query)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        $products = Product::search($query);
        header('Content-Type: application/json');
        echo json_encode($products);
    }
}
