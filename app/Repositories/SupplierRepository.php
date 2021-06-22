<?php
namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierRepository{

    public function getSuppliers($filter=[]){
        if($filter){
            $filter = (object) $filter;
        }
        $suppliers = Supplier::query()->latest('id');
        $params = [];
        if(isset($filter->search_txt) && !empty($filter->search_txt)){
            $suppliers = $suppliers->where(function ($q) use ($filter){
                $q->where('name','LIKE','%'.$filter->search_txt.'%')
                    ->orWhere('email','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('mobile','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('address','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('shop_name','LIKE', '%'.$filter->search_txt.'%');
            });
            $params['search_txt'] = $filter->search_txt;
        }
        if(isset($filter->paginate)){
            $suppliers = $suppliers->paginate($filter->paginate);
            $params['paginate'] = $filter->paginate;
            if (!empty($params)) {
                $suppliers = $suppliers->appends($params);
            }
            return $suppliers;
        }
        return $suppliers->get();
    }

    public function getSupplierById($id){
        return Supplier::findOrFail($id);
    }

    public function createSupplier($request){
        try{
            DB::beginTransaction();
            $data = [
                'name'    => $request -> name,
                'email'   => $request -> email,
                'mobile'  => $request -> mobile,
                'address' => $request -> address,
            ];
            if ($request -> shop_name){
                $data['shop_name'] = $request -> shop_name;
            }
            if ($request->photo) {
                $data['photo'] = uploadPhoto($request -> photo,'suppliers');
            }
            Supplier::create($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Supplier has been Created Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateSupplier($id,$request){
        $supplier = $this->getSupplierById($id);
        $oldPhoto = $supplier -> getRawOriginal('photo');
        try{
            DB::beginTransaction();
            $data = [
                'name'    => $request -> name,
                'email'   => $request -> email,
                'mobile'  => $request -> mobile,
                'address' => $request -> address,
            ];
            if ($request -> shop_name){
                $data['shop_name'] = $request -> shop_name;
            }else {
                $data['shop_name'] = null;
            }
            if ($request -> newPhoto) {
                $data['photo'] = uploadPhoto($request -> newPhoto,'suppliers');
            }
            $supplier->update($data);
            if (($request -> newPhoto) && !empty($oldPhoto)){
                if (file_exists($oldPhoto)){
                    unlink($oldPhoto);
                }

            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Supplier has been Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function destroySupplier($id){
        $supplier = $this->getSupplierById($id);
        try {
            $photo = $supplier -> getRawOriginal('photo');
            DB::beginTransaction();
            $supplier->delete();
            if (!empty($photo)){
                if (file_exists($photo)){
                    unlink($photo);
                }
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Supplier has been Deleted Successfully'
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
