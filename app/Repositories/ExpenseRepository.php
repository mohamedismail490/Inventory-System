<?php
namespace App\Repositories;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseRepository{

    public function getExpenses($filter=[]){
        if($filter){
            $filter = (object) $filter;
        }
        $expenses = Expense::query()->latest('id');
        $params = [];
        if(isset($filter->today) && $filter->today){
            $now = Carbon::now()->format('Y-m-d');
            $expenses = $expenses->whereDate('expense_date', $now);
        }
        if(isset($filter->search_txt) && !empty($filter->search_txt)){
            $expenses = $expenses->where(function ($q) use ($filter){
                $q->where('details','LIKE','%'.$filter->search_txt.'%')
                    ->orWhere('amount','LIKE', '%'.$filter->search_txt.'%');
            });
            $params['search_txt'] = $filter->search_txt;
        }
        if (isset($filter->sum) && !empty($filter->sum)){
            $expenses = $expenses->sum($filter->sum);
        }else{
            if(isset($filter->paginate)){
                $expenses = $expenses->paginate($filter->paginate);
                $params['paginate'] = $filter->paginate;
                if (!empty($params)) {
                    $expenses = $expenses->appends($params);
                }
                return $expenses;
            }
            $expenses = $expenses->get();
        }

        return $expenses;
    }

    public function getExpenseById($id){
        return Expense::findOrFail($id);
    }

    public function createExpense($request){
        try{
            DB::beginTransaction();
            $data = [
                'details'      => str_replace(PHP_EOL, '', strip_tags( trim( html_entity_decode( $request -> details ) ) ) ),
                'amount'       => round( $request -> amount , 2),
                'expense_date' => Carbon::now()->format('Y-m-d')
            ];
            Expense::create($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Expense has been Created Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateExpense($id,$request){
        $expense = $this->getExpenseById($id);
        try{
            DB::beginTransaction();
            $data = [
                'details' => str_replace(PHP_EOL, '', strip_tags( trim( html_entity_decode( $request -> details ) ) ) ),
                'amount'  => round( $request -> amount , 2)
            ];
            $expense->update($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Expense has been Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function destroyExpense($id){
        $expense = $this->getExpenseById($id);
        try {
            DB::beginTransaction();
            $expense->delete();
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Expense has been Deleted Successfully'
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }
}
