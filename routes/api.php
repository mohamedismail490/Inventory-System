<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([ 'middleware' => 'api'], function () {
    //Employees
    Route::group(['prefix' => 'employees'], function () {
        Route::get('', [Api\EmployeeController::class, 'index']);
        Route::post('', [Api\EmployeeController::class, 'store']);
        Route::get('{employee}', [Api\EmployeeController::class, 'show']);
        Route::patch('{employee}', [Api\EmployeeController::class, 'update']);
        Route::delete('{employee}', [Api\EmployeeController::class, 'destroy']);
        //Employee Salary
        Route::post('{employee}/pay_salary', [Api\EmployeeController::class, 'paySalary']);
        Route::get('paid/salaries', [Api\EmployeeController::class, 'paidSalaries']);
        Route::get('salaries/view/{salary}', [Api\EmployeeController::class, 'viewSalaries']);
        Route::get('salaries/{salary}', [Api\EmployeeController::class, 'showSalary']);
        Route::patch('salaries/{salary}', [Api\EmployeeController::class, 'updateSalary']);
    });


    //Suppliers
    Route::group(['prefix' => 'suppliers'], function () {
        Route::get('', [Api\SupplierController::class, 'index']);
        Route::post('', [Api\SupplierController::class, 'store']);
        Route::get('{supplier}', [Api\SupplierController::class, 'show']);
        Route::patch('{supplier}', [Api\SupplierController::class, 'update']);
        Route::delete('{supplier}', [Api\SupplierController::class, 'destroy']);
    });

    //Categories
    Route::group(['prefix' => 'categories'], function () {
        Route::get('', [Api\CategoryController::class, 'index']);
        Route::post('', [Api\CategoryController::class, 'store']);
        Route::get('{category}', [Api\CategoryController::class, 'show']);
        Route::patch('{category}', [Api\CategoryController::class, 'update']);
        Route::delete('{category}', [Api\CategoryController::class, 'destroy']);
    });

    //Products
    Route::group(['prefix' => 'products'], function () {
        Route::get('', [Api\ProductController::class, 'index']);
        Route::post('', [Api\ProductController::class, 'store']);
        Route::get('{product}', [Api\ProductController::class, 'show']);
        Route::patch('{product}', [Api\ProductController::class, 'update']);
        Route::delete('{product}', [Api\ProductController::class, 'destroy']);
        Route::post('{product}/stock/update', [Api\ProductController::class, 'stockUpdate']);
    });

    //Expenses
    Route::group(['prefix' => 'expenses'], function () {
        Route::get('', [Api\ExpenseController::class, 'index']);
        Route::post('', [Api\ExpenseController::class, 'store']);
        Route::get('{expense}', [Api\ExpenseController::class, 'show']);
        Route::patch('{expense}', [Api\ExpenseController::class, 'update']);
        Route::delete('{expense}', [Api\ExpenseController::class, 'destroy']);
    });

    //Customers
    Route::group(['prefix' => 'customers'], function () {
        Route::get('', [Api\CustomerController::class, 'index']);
        Route::post('', [Api\CustomerController::class, 'store']);
        Route::get('{customer}', [Api\CustomerController::class, 'show']);
        Route::patch('{customer}', [Api\CustomerController::class, 'update']);
        Route::delete('{customer}', [Api\CustomerController::class, 'destroy']);
    });

});
