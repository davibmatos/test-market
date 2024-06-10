<?php

namespace App\Controller;

use App\Model\ProductType;
use App\Service\ProductService;

class ProductTypeController extends BaseController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index()
    {
        try {
            $this->successResponse(ProductType::findAll(), "Todos os tipos de produtos recuperados com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 404);
        }
    }

    public function store()
    {
        try {
            $productType = $this->productService->createProductType(json_decode(file_get_contents('php://input'), true));
            $this->successResponse($productType, "Tipo de produto criado com sucesso");
        } catch (\Exception $exception) {
            if ($exception->getMessage() == "O tipo de produto já existe.") {
                $this->errorResponse($exception->getMessage(), 409);
            } else {
                $this->errorResponse($exception->getMessage(), 500);
            }
        }
    }

    public function edit($id)
    {
        try {
            $this->productService->updateProductType($id, json_decode(file_get_contents('php://input'), true));
            $this->successResponse(null, "Tipo de produto atualizado com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->productService->deleteProductType($id);
            $this->successResponse(null, "Tipo de produto excluído com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 500);
        }
    }
}
