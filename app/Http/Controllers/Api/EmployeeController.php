<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeePaySalaryRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Requests\EmployeeUpdateSalaryRequest;
use App\Models\Employee;
use App\Models\Salary;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
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

    public function paySalary(Employee $employee, EmployeePaySalaryRequest $request) {
        $pay = $this->employeeRepo->payEmployeeSalary($employee -> id, $request);
        return response()->json($pay);
    }

    public function paidSalaries() {
        $salaries = $this->employeeRepo->getMonthsSalaries();
        return response()->json($salaries);
    }

    public function viewSalaries($salary){
        $salaries = $this->employeeRepo->getSalariesByMonth($salary);
        return $salaries;
    }

    public function showSalary(Salary $salary){
        $salary = $this->employeeRepo->getEmployeeSalaryById($salary -> id);
        return response()->json($salary);
    }

    public function updateSalary(EmployeeUpdateSalaryRequest $request, Salary $salary) {
        $update = $this->employeeRepo->updateEmployeeSalary($salary -> id, $request);
        return response()->json($update);
    }
}
