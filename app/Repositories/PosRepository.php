<?php
namespace App\Repositories;

use App\Models\Pos;
use Illuminate\Support\Facades\DB;

class PosRepository{

    public $productRepo;
    public function __construct(ProductRepository $productRepo){
        $this -> productRepo = $productRepo;
    }

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
                'message' => ($isExisted) ? 'Cart Product Updated Successfully' : 'Product Added To Cart Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function incrementCart($id){
        $cartItem = $this -> getCartItemById($id);
        $product  = $this -> productRepo -> getProductById($cartItem -> product_id);
        try{
            DB::beginTransaction();
            $data = [
                'name'      => $product -> name,
                'quantity'  => ($cartItem -> quantity + 1),
                'price'     => round( $product -> selling_price , 2),
                'sub_total' => round( ($product -> selling_price * ($cartItem -> quantity + 1)) , 2),
            ];
            $cartItem -> update($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Cart Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function decrementCart($id){
        $cartItem = $this -> getCartItemById($id);
        if ($cartItem -> quantity == 1){
            return (object)[
                'status'  => false,
                'message' => 'Quantity Minimum is 1 !!'
            ];
        }
        $product  = $this -> productRepo -> getProductById($cartItem -> product_id);
        try{
            DB::beginTransaction();
            $data = [
                'name'      => $product -> name,
                'quantity'  => ($cartItem -> quantity - 1),
                'price'     => round( $product -> selling_price , 2),
                'sub_total' => round( ($product -> selling_price * ($cartItem -> quantity - 1)) , 2),
            ];
            $cartItem -> update($data);
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Cart Updated Successfully',
            ];
        }catch (\Exception $e){
            DB::rollback();
            return (object)[
                'status'  => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function removeFromCart($id){
        $cartItem = $this -> getCartItemById($id);
        try {
            DB::beginTransaction();
            $cartItem->delete();
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Product Removed from Cart Successfully'
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
