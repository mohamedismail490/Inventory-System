<?php
namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingRepository{

    public function getSettings(){
        return Setting::pluck('value','key');
    }

    public function getVat(){
        $vat = Setting::where('key','vat')->first();
        if (!empty($vat)){
            $value = round($vat -> value, 2);
        }else {
            $value = 0;
        }
        return $value;
    }

    public function updateSetting($request){
        try{
            DB::beginTransaction();
            Setting::truncate();
            foreach ($request->all() as $key => $value){
                Setting::create([
                    'key'   => $key,
                    'value' => $value
                ]);
            }
            DB::commit();
            return (object)[
                'status'  => true,
                'message' => 'Settings have been Updated Successfully'
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
