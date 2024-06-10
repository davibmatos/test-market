<?php

namespace App\Controller;

use App\Factory\SaleFactory;
use App\Factory\SaleItemFactory;
use App\Service\SaleService;

class SaleController extends BaseController
{
    private $saleService;

    public function __construct()
    {
        $saleFactory = new SaleFactory();
        $saleItemFactory = new SaleItemFactory();
        $this->saleService = new SaleService($saleFactory, $saleItemFactory);
    }

    public function store()
    {
        try {
            $saleData = json_decode(file_get_contents('php://input'), true);
            $sale = $this->saleService->createSale($saleData);
            $this->successResponse($sale, "Venda criada com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 500);
        }
    }

    public function index()
    {
        try {
            $this->successResponse($this->saleService->getAllSales(), "Vendas recuperadas com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 500);
        }
    }

    public function viewDetails($id)
    {
        try {
            $this->successResponse($this->saleService->getSaleDetails($id), "Detalhes da venda recuperados com sucesso");
        } catch (\Exception $exception) {
            $this->errorResponse($exception->getMessage(), 500);
        }
    }
}
