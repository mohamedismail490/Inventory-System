<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pos;
use App\Models\Product;
use App\Repositories\PosRepository;
use App\Repositories\ProductRepository;

class PosController extends Controller
{
    public $posRepo, $productRepo;
    public function __construct(PosRepository $posRepo, ProductRepository $productRepo){
        $this->posRepo     = $posRepo;
        $this->productRepo = $productRepo;
    }

    public function index() {
        $cartItems = $this->posRepo->getCartItems();
        return $cartItems;
    }

    public function getCategoryProducts($id){
        $categoryProducts = $this -> productRepo -> getProducts([
            'category' => $id
        ]);
        return $categoryProducts;
    }

    public function addToCart($id) {
        $product = $this -> productRepo -> getProductById($id);
        $add     = $this->posRepo->addToCart($product);
        return response()->json($add);
    }

    public function removeFromCart(Pos $pos) {
        $remove = $this->posRepo->removeFromCart($pos -> id);
        return response()->json($remove);
    }

    public function incrementCart(Pos $pos) {
        $increment = $this->posRepo->incrementCart($pos -> id);
        return response()->json($increment);
    }

    public function decrementCart(Pos $pos) {
        $decrement = $this->posRepo->decrementCart($pos -> id);
        return response()->json($decrement);
    }
}
