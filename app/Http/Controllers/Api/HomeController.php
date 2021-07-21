<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderSearchRequest;
use App\Models\Order;
use App\Repositories\ExpenseRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;

class HomeController extends Controller
{
    public $orderRepo, $expenseRepo, $productRepo;
    public function __construct(OrderRepository $orderRepo, ExpenseRepository $expenseRepo, ProductRepository $productRepo){
        $this->orderRepo   = $orderRepo;
        $this->expenseRepo = $expenseRepo;
        $this->productRepo = $productRepo;
    }

    public function todaySell() {
        $orders = $this->orderRepo->getOrders([
            'today' => true,
            'sum'   => 'total'
        ]);
        return round($orders, 2);
    }

    public function todayIncome() {
        $orders = $this->orderRepo->getOrders([
            'today' => true,
            'sum'   => 'pay'
        ]);
        return round($orders, 2);
    }

    public function todayDue() {
        $orders = $this->orderRepo->getOrders([
            'today' => true,
            'sum'   => 'due'
        ]);
        return round($orders, 2);
    }

    public function todayExpense() {
        $expenses = $this->expenseRepo->getExpenses([
            'today' => true,
            'sum'   => 'amount'
        ]);
        return round($expenses, 2);
    }

    public function todayOrders() {
        $orders = $this->orderRepo->getOrders([
            'today' => true
        ]);
        return $orders;
    }

    public function stockOutProducts() {
        $products = $this->productRepo->getProducts([
            'stockOut' => true
        ]);
        return $products;
    }



    public function search(OrderSearchRequest $request) {
        $orders = $this->orderRepo->getOrders([
            'date' => $request -> date
        ]);
        return $orders;
    }

    public function store(OrderCreateRequest $request) {
        $store = $this->orderRepo->createOrder($request);
        return response()->json($store);
    }

    public function show(Order $order){
        $order = $this->orderRepo->getOrderById($order -> id);
        return response()->json($order);
    }
}
