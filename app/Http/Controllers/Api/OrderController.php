<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderSearchRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderController extends Controller
{
    public $orderRepo;
    public function __construct(OrderRepository $orderRepo){
        $this->orderRepo = $orderRepo;
    }

    public function index() {
        $orders = $this->orderRepo->getOrders();
        return $orders;
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
