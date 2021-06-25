<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    public $customerRepo;
    public function __construct(CustomerRepository $customerRepo){
        $this->customerRepo = $customerRepo;
    }

    public function index() {
        $customers = $this->customerRepo->getCustomers();
        return response()->json($customers);
    }

    public function store(CustomerCreateRequest $request) {
        $create = $this->customerRepo->createCustomer($request);
        return response()->json($create);
    }

    public function show(Customer $customer){
        $customer = $this->customerRepo->getCustomerById($customer -> id);
        return response()->json($customer);
    }

    public function update(CustomerUpdateRequest $request, Customer $customer) {
        $update = $this->customerRepo->updateCustomer($customer -> id, $request);
        return response()->json($update);
    }

    public function destroy(Customer $customer) {
        $destroy = $this->customerRepo->destroyCustomer($customer -> id);
        return response()->json($destroy);
    }
}
