<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseCreateRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Models\Expense;
use App\Repositories\ExpenseRepository;

class ExpenseController extends Controller
{
    public $expenseRepo;
    public function __construct(ExpenseRepository $expenseRepo){
        $this->expenseRepo = $expenseRepo;
    }

    public function index() {
        $expenses = $this->expenseRepo->getExpenses();
        return response()->json($expenses);
    }

    public function store(ExpenseCreateRequest $request) {
        $create = $this->expenseRepo->createExpense($request);
        return response()->json($create);
    }

    public function show(Expense $expense){
        $expense = $this->expenseRepo->getExpenseById($expense -> id);
        return response()->json($expense);
    }

    public function update(ExpenseUpdateRequest $request, Expense $expense) {
        $update = $this->expenseRepo->updateExpense($expense -> id, $request);
        return response()->json($update);
    }

    public function destroy(Expense $expense) {
        $destroy = $this->expenseRepo->destroyExpense($expense -> id);
        return response()->json($destroy);
    }
}
