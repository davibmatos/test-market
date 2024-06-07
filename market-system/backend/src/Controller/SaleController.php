<?php

namespace App\Controller;

use App\Service\SaleService;

class SaleController extends BaseController
{
    private $saleService;

    public function __construct()
    {
        $this->saleService = new SaleService();
    }

    public function addSale()
    {
        try {
            $saleData = json_decode(file_get_contents('php://input'), true);
            $sale = $this->saleService->createSale($saleData);
            $this->successResponse($sale, "Venda criada com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function viewSales()
    {
        try {
            $sales = $this->saleService->getAllSales();
            $this->successResponse($sales, "Vendas recuperadas com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function viewSaleDetails($id)
    {
        try {
            $sale = $this->saleService->getSaleDetails($id);
            if (!$sale) {
                throw new \Exception("Venda não encontrada");
            }
            $this->successResponse($sale, "Detalhes da venda recuperados com sucesso");
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

}
