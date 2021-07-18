<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStockUpdateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Repositories\PosRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

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

    public function addToCart($id, Request $request) {
        $product = $this -> productRepo -> getProductById($id);
        $add     = $this->posRepo->addToCart($product, $request);
        return response()->json($add);
    }

    public function show(Product $product){
        $product = $this->productRepo->getProductById($product -> id);
        return response()->json($product);
    }

    public function update(ProductUpdateRequest $request, Product $product) {
        $update = $this->productRepo->updateProduct($product -> id, $request);
        return response()->json($update);
    }

    public function destroy(Product $product) {
        $destroy = $this->productRepo->destroyProduct($product -> id);
        return response()->json($destroy);
    }

    public function stockUpdate(ProductStockUpdateRequest $request, Product $product) {
        $updateStock = $this->productRepo->updateProductStock($product -> id, $request);
        return response()->json($updateStock);
    }
}
