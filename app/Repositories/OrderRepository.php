<?php
namespace App\Repositories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Pos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository{

    public $posRepo, $settingRepo;
    public function __construct(PosRepository $posRepo, SettingRepository $settingRepo){
        $this -> posRepo = $posRepo;
        $this -> settingRepo = $settingRepo;
    }

    public function getOrders($filter = []){
        $orders = Order::latest('id');
        if($filter){
            $filter = (object) $filter;
        }
        $params = [];
        if(isset($filter->today) && $filter->today){
            $now = Carbon::now()->format('Y-m-d');
            $orders = $orders->where('order_date', $now);
        }
        if(isset($filter->date) && !empty($filter->date)){
            $orders = $orders->where('order_date', $filter->date);
        }
        if (isset($filter->sum) && !empty($filter->sum)){
            $orders = $orders->sum($filter->sum);
        }else{
            if(isset($filter->paginate)){
                $orders = $orders->paginate(intval($filter->paginate));
                $params['paginate'] = intval($filter->paginate);
                if (!empty($params)) {
                    $orders = $orders->appends($params);
                }
                return $orders;
            }
            $orders = $orders->get();
        }
        return $orders;
    }

    public function getOrderById($id){
        return Order::findOrFail($id);
    }

    public function createOrder($request){
        $cartQuery = Pos::query();
        $cartCount = $cartQuery -> count();
        if ($cartCount <= 0){
            return (object)[
                'status'  => false,
                'message' => 'Your Cart is Empty...'
            ];
        }
        $cartItems = $cartQuery -> get();
        $isInvalidItem = false;
        $invalidItemsProductsNames = "";
        foreach ($cartItems as $item){
            if (empty($item -> product)){
                $isInvalidItem = true;
                $invalidItemsProductsNames .= " " . $item -> name . " Is Not Available - ";
            }elseif ($item -> product -> quantity < 1){
                $isInvalidItem = true;
                $invalidItemsProductsNames .= " Stock Out for " . $item -> product -> name . " - ";
            }elseif ($item -> product -> quantity < $item -> quantity){
                $isInvalidItem = true;
                $invalidItemsProductsNames .= " Maximum Quantity is ( " . $item -> product -> quantity . " ) for " . $item -> product -> name . " - ";
            }
        }
        if ($isInvalidItem){
            return (object)[
                'status'  => false,
                'message' => trim(trim($invalidItemsProductsNames),'-')
            ];
        }
        $cartQuantity = intval($cartQuery -> sum('quantity'));
        $cartSubTotal = floatval($cartQuery -> sum('sub_total'));
        $vat          = $this -> settingRepo -> getVat();
        $vatValue     = floatval($cartSubTotal * ($vat / 100));
        $total        = floatval($vatValue + $cartSubTotal);
        $customer = Customer::find($request -> customer_id);
        try{
            DB::beginTransaction();
            $data = [
                'customer_id'   => $request -> customer_id,
                'customer_name' => !empty($customer) ? ($customer -> name ?? null) : null,
                'quantity'      => $cartQuantity,
                'sub_total'     => round($cartSubTotal, 2),
                'vat'           => $vat,
                'vat_value'     => $vatValue,
                'total'         => $total,
                'pay'           => round($request -> pay, 2),
                'due'           => !empty($request -> due) ? round($request -> due, 2) : 0,
                'pay_by'        => $request -> pay_by,
                'order_date'    => Carbon::now()->format('Y-m-d'),
                'order_month'   => Carbon::now()->format('F'),
                'order_year'    => Carbon::now()->format('Y')
            ];

            $newOrder = Order::create($data);
            if ($newOrder){
                foreach ($cartItems as $cartItem){
                    $newOrder -> order_details() -> create([
                        'product_id' => $cartItem -> product_id,
                        'quantity'   => $cartItem -> quantity,
                        'price'      => $cartItem -> price,
                        'sub_total'  => $cartItem -> sub_total,
                    ]);
                    $newProductQty = ($cartItem -> product -> quantity - $cartItem -> quantity);
                    $cartItem -> product() -> update(['quantity' => ($newProductQty <= 0 ? 0 : $newProductQty)]);
                    $cartItem -> delete();
                }
            }

            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Order has been Created Successfully',
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
