<?php
namespace App\Repositories;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductRepository{

    public function getProducts($filter=[]){
        if($filter){
            $filter = (object) $filter;
        }
        $products = Product::query()->latest('id');
        $params = [];
        if(isset($filter->search_txt) && !empty($filter->search_txt)){
            $products = $products->where(function ($q) use ($filter){
                $q->where('name','LIKE','%'.$filter->search_txt.'%')
                    ->orWhere('code','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('root','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('address','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('buying_price','LIKE', '%'.$filter->search_txt.'%')
                    ->orWhere('selling_price','LIKE', '%'.$filter->search_txt.'%');
            });
            $params['search_txt'] = $filter->search_txt;
        }
        if(isset($filter->stockOut) && $filter->stockOut){
            $products = $products->where('quantity', '<', 1);
        }
        if(isset($filter->category) && !empty($filter->category)){
            $products = $products->where('category_id', $filter->category);
            $params['category'] = $filter->category;
        }
        if(isset($filter->supplier) && !empty($filter->supplier)){
            $products = $products->where('supplier_id', $filter->supplier);
            $params['supplier'] = $filter->supplier;
        }
        if(isset($filter->paginate)){
            $products = $products->paginate($filter->paginate);
            $params['paginate'] = $filter->paginate;
            if(!empty($params)){
                $products = $products->appends($params);
            }
            return $products;
        }
        return $products->get();
    }

    public function getProductById($id){
        return Product::findOrFail($id);
    }

    public function createProduct($request){
        try{
            DB::beginTransaction();
            $data = [
                'name'          => $request -> name,
                'code'          => $request -> code,
                'category_id'   => $request -> category_id,
                'supplier_id'   => $request -> supplier_id,
                'buying_price'  => round( $request -> buying_price , 2),
                'selling_price' => round( $request -> selling_price , 2),
                'buying_date'   => Carbon::parse($request->buying_date)->format('Y-m-d'),
                'quantity'      => $request -> quantity
            ];
            if ($request -> root){
                $data['root'] = $request -> root;
            }
            if ($request->image) {
                $data['image'] = uploadPhoto($request -> image,'products');
            }
            Product::create($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Product has been Created Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateProduct($id,$request){
        $product = $this->getProductById($id);
        $oldImage = $product -> getRawOriginal('image');
        try{
            DB::beginTransaction();
            $data = [
                'name'          => $request -> name,
                'code'          => $request -> code,
                'category_id'   => $request -> category_id,
                'supplier_id'   => $request -> supplier_id,
                'buying_price'  => round( $request -> buying_price , 2),
                'selling_price' => round( $request -> selling_price , 2),
                'buying_date'   => Carbon::parse($request->buying_date)->format('Y-m-d'),
                'quantity'      => $request -> quantity
            ];
            if ($request -> root){
                $data['root'] = $request -> root;
            }else {
                $data['root'] = null;
            }
            if ($request -> newImage) {
                $data['image'] = uploadPhoto($request -> newImage,'products');
            }
            $product->update($data);
            if ($request -> newImage && !empty($oldImage)){
                if (file_exists($oldImage)){
                    unlink($oldImage);
                }
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Product has been Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function destroyProduct($id){
        $product = $this -> getProductById($id);
        $image   = $product -> getRawOriginal('image');
        try {
            DB::beginTransaction();
            $product->delete();
            if (!empty($image)){
                if (file_exists($image)){
                    unlink($image);
                }
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Product has been Deleted Successfully'
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateProductStock($id,$request){
        $product = $this->getProductById($id);
        try{
            DB::beginTransaction();
            $product->update(['quantity' => intval($request -> quantity)]);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Product Stock has been Updated Successfully',
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
