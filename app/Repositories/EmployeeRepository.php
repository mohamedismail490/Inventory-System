<?php
namespace App\Repositories;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class EmployeeRepository{

    public function getEmployees($filter=[]){
        if($filter){
            $filter = (object) $filter;
        }
        $employees = Employee::query()->latest('id');
        $params = [];
        if(isset($filter->search_txt) && !empty($filter->search_txt)){
            $employees = $employees->where(function ($q) use ($filter){
                $q->where('name','LIKE','%'.$filter->search_txt.'%')
                    ->orWhere('email','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('mobile','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('address','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('salary','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('nid','LIKE', '%'.$filter->search_txt.'%');
            });
            $params['search_txt'] = $filter->search_txt;
        }
        if(isset($filter->paginate)){
            $employees = $employees->paginate($filter->paginate);
            $params['paginate'] = $filter->paginate;
            if(!empty($params)){
                $employees = $employees->appends($params);
            }
            return $employees;
        }
        return $employees->get();
    }

    public function getEmployeeById($id){
        return Employee::findOrFail($id);
    }

    public function createEmployee($request){
        try{
            DB::beginTransaction();
            $data = [
                'name'         => $request -> name,
                'email'        => $request -> email,
                'mobile'       => $request -> mobile,
                'address'      => $request -> address,
                'salary'       => $request -> salary,
                'joining_date' => Carbon::parse($request->joining_date)->format('Y-m-d'),
            ];
            if ($request -> nid){
                $data['nid'] = $request -> nid;
            }
            if ($request->photo) {
                $data['photo'] = uploadPhoto($request -> photo,'employees');
            }
            Employee::create($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Employee has been Created Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateEmployee($id,$request){
        $employee = $this->getEmployeeById($id);
        $oldPhoto = $employee -> getRawOriginal('photo');
        try{
            DB::beginTransaction();
            $data = [
                'name'         => $request -> name,
                'email'        => $request -> email,
                'mobile'       => $request -> mobile,
                'address'      => $request -> address,
                'salary'       => $request -> salary,
                'joining_date' => Carbon::parse($request->joining_date)->format('Y-m-d'),
            ];
            if ($request -> nid){
                $data['nid'] = $request -> nid;
            }else {
                $data['nid'] = null;
            }
            if ($request -> newPhoto) {
                $data['photo'] = uploadPhoto($request -> newPhoto,'employees');
            }
            $employee->update($data);
            if ($request -> newPhoto && !empty($oldPhoto)){
                if (file_exists($oldPhoto)){
                    unlink($oldPhoto);
                }
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Employee has been Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function destroyEmployee($id){
        $employee = $this->getEmployeeById($id);
        try {
            $photo = $employee -> getRawOriginal('photo');
            DB::beginTransaction();
            $employee->delete();
            if (!empty($photo)){
                if (file_exists($photo)){
                    unlink($photo);
                }
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Employee has been Deleted Successfully'
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
