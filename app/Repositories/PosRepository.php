<?php
namespace App\Repositories;

use App\Models\Pos;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PosRepository{

    public function getCartItems(){
        return Pos::latest('id')->get();
    }

    public function getCartItemById($id){
        return Pos::findOrFail($id);
    }

    public function addToCart($product){
        $existedCartItem = Pos::query()->where('product_id', $product -> id)->first();
        $isExisted = false;
        if (!empty($existedCartItem)){
            $isExisted = true;
        }
        try{
            DB::beginTransaction();
            $data = [
                'name'      => $product -> name,
                'quantity'  => ($isExisted ? ($existedCartItem -> quantity + 1) : 1),
                'price'     => round( $product -> selling_price , 2),
                'sub_total' => round( ($product -> selling_price * ($isExisted ? ($existedCartItem -> quantity + 1) : 1)) , 2),
            ];
            if ($isExisted){
                $existedCartItem -> update($data);
            }else{
                $data['product_id'] = $product -> id;
                Pos::create($data);
            }

            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Product Added To Cart Successfully',
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
