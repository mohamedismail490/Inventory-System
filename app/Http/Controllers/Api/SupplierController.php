<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierCreateRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;

class SupplierController extends Controller
{
    public $supplierRepo;
    public function __construct(SupplierRepository $supplierRepo){
        $this->supplierRepo = $supplierRepo;
    }

    public function index() {
        $suppliers = $this->supplierRepo->getSuppliers();
        return response()->json($suppliers);
    }

    public function store(SupplierCreateRequest $request) {
        $create = $this->supplierRepo->createSupplier($request);
        return response()->json($create);
    }

    public function show(Supplier $supplier){
        $supplier = $this->supplierRepo->getSupplierById($supplier -> id);
        return response()->json($supplier);
    }

    public function update(SupplierUpdateRequest $request, Supplier $supplier) {
        $update = $this->supplierRepo->updateSupplier($supplier -> id, $request);
        return response()->json($update);
    }

    public function destroy(Supplier $supplier) {
        $destroy  = $this->supplierRepo->destroySupplier($supplier -> id);
        return response()->json($destroy);
    }
}
