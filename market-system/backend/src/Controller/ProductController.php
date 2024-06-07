<?php

namespace App\Controller;

use App\Model\Product;
use App\Model\ProductType;
use App\Service\ProductService;

class ProductController extends BaseController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function viewProducts()
    {
        $products = Product::findAll();
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function addProduct()
    {
        try {
            $product = $this->productService->createProduct(json_decode(file_get_contents('php://input'), true));
            $this->successResponse($product, "Produto criado com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function editProduct($id)
    {
        try {
            $this->productService->updateProduct($id, json_decode(file_get_contents('php://input'), true));
            $this->successResponse(null, "Produto atualizado com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $this->productService->deleteProduct($id);
            $this->successResponse(null, "Produto excluído com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function searchProducts()
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

    public function viewProductTypes()
    {
        try {
            $this->successResponse(ProductType::findAll(), "Todos os tipos de produtos recuperados com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 404);
        }
    }


    public function addProductType()
    {
        try {
            $productType = $this->productService->createProductType(json_decode(file_get_contents('php://input'), true));
            $this->successResponse($productType, "Tipo de produto criado com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function editProductType($id)
    {
        try {
            $this->productService->updateProductType($id, json_decode(file_get_contents('php://input'), true));
            $this->successResponse(null, "Tipo de produto atualizado com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function deleteProductType($id)
    {
        try {
            $this->productService->deleteProductType($id);
            $this->successResponse(null, "Tipo de produto excluído com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
