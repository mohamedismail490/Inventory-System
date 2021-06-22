<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    public $categoryRepo;
    public function __construct(CategoryRepository $categoryRepo){
        $this->categoryRepo = $categoryRepo;
    }

    public function index() {
        $categories = $this->categoryRepo->getCategories();
        return response()->json($categories);
    }

    public function store(CategoryCreateRequest $request) {
        $create = $this->categoryRepo->createCategory($request);
        return response()->json($create);
    }

    public function show(Category $category){
        $category = $this->categoryRepo->getCategoryById($category -> id);
        return response()->json($category);
    }

    public function update(CategoryUpdateRequest $request, Category $category) {
        $update = $this->categoryRepo->updateCategory($category -> id, $request);
        return response()->json($update);
    }

    public function destroy(Category $category) {
        $destroy = $this->categoryRepo->destroyCategory($category -> id);
        return response()->json($destroy);
    }
}
