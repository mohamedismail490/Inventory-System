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
    });
});
