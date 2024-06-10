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
        echo json_encode(Product::findAll());
    }

    public function store()
    {
        try {
            $this->successResponse($this->productService->createProduct(json_decode(file_get_contents('php://input'), true)), "Produto criado com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        try {
            $this->productService->updateProduct($id, json_decode(file_get_contents('php://input'), true));
            $this->successResponse(null, "Produto atualizado com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->productService->deleteProduct($id);
            $this->successResponse(null, "Produto excluído com sucesso");
        } catch (\Exception $exception) {
            if ($exception->getCode() == 23000) {
                $this->errorResponse("O produto não pode ser deletado pois já existe uma venda associada a ele.", 500);
                return;
            }
            $this->errorResponse($exception->getMessage(), 500);
        }
    }


    public function search()
    {
        $query = $_GET['query'] ?? '';

        if (empty($query)) {
            echo json_encode([]);
            return;
        }
        echo json_encode(Product::search($query));
    }
}
