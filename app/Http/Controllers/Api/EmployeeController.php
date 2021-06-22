<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public $employeeRepo;
    public function __construct(EmployeeRepository $employeeRepo){
        $this->employeeRepo = $employeeRepo;
    }

    public function index() {
        $employees = $this->employeeRepo->getEmployees();
        return response()->json($employees);
    }

    public function store(EmployeeCreateRequest $request) {
        $create = $this->employeeRepo->createEmployee($request);
        return response()->json($create);
    }

    public function show(Employee $employee){
        $employee = $this->employeeRepo->getEmployeeById($employee -> id);
        return response()->json($employee);
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee) {
        $update = $this->employeeRepo->updateEmployee($employee -> id, $request);
        return response()->json($update);
    }

    public function destroy(Employee $employee) {
        $destroy  = $this->employeeRepo->destroyEmployee($employee -> id);
        return response()->json($destroy);
    }
}
