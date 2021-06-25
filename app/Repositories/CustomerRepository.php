<?php
namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository{

    public function getCustomers($filter=[]){
        if($filter){
            $filter = (object) $filter;
        }
        $customers = Customer::query()->latest('id');
        $params = [];
        if(isset($filter->search_txt) && !empty($filter->search_txt)){
            $customers = $customers->where(function ($q) use ($filter){
                $q->where('name','LIKE','%'.$filter->search_txt.'%')
                    ->orWhere('email','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('mobile','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('address','LIKE', '%'.$filter->search_txt.'%');
            });
            $params['search_txt'] = $filter->search_txt;
        }
        if(isset($filter->paginate)){
            $customers = $customers->paginate($filter->paginate);
            $params['paginate'] = $filter->paginate;
            if (!empty($params)) {
                $customers = $customers->appends($params);
            }
            return $customers;
        }
        return $customers->get();
    }

    public function getCustomerById($id){
        return Customer::findOrFail($id);
    }

    public function createCustomer($request){
        try{
            DB::beginTransaction();
            $data = [
                'name'    => $request -> name,
                'email'   => $request -> email,
                'mobile'  => $request -> mobile,
                'address' => $request -> address,
            ];
            if ($request->photo) {
                $data['photo'] = uploadPhoto($request -> photo,'customers');
            }
            Customer::create($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Customer has been Created Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateCustomer($id,$request){
        $customer = $this->getCustomerById($id);
        $oldPhoto = $customer -> getRawOriginal('photo');
        try{
            DB::beginTransaction();
            $data = [
                'name'    => $request -> name,
                'email'   => $request -> email,
                'mobile'  => $request -> mobile,
                'address' => $request -> address,
            ];
            if ($request -> newPhoto) {
                $data['photo'] = uploadPhoto($request -> newPhoto,'customers');
            }
            $customer->update($data);
            if (($request -> newPhoto) && !empty($oldPhoto)){
                if (file_exists($oldPhoto)){
                    unlink($oldPhoto);
                }
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Customer has been Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function destroyCustomer($id){
        $customer = $this->getCustomerById($id);
        try {
            $photo = $customer -> getRawOriginal('photo');
            DB::beginTransaction();
            $customer->delete();
            if (!empty($photo)){
                if (file_exists($photo)){
                    unlink($photo);
                }
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Customer has been Deleted Successfully'
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
