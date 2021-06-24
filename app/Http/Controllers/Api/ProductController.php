<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductStockUpdateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public $productRepo;
    public function __construct(ProductRepository $productRepo){
        $this->productRepo = $productRepo;
    }

    public function index() {
        $products = $this->productRepo->getProducts();
        return response()->json($products);
    }

    public function store(ProductCreateRequest $request) {
        $create = $this->productRepo->createProduct($request);
        return response()->json($create);
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
