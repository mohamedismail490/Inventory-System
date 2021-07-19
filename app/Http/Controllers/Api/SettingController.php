<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingUpdateRequest;
use App\Repositories\SettingRepository;

class SettingController extends Controller
{
    public $settingRepo;
    public function __construct(SettingRepository $settingRepo){
        $this->settingRepo = $settingRepo;
    }

    public function index() {
        $settings = $this->settingRepo->getSettings();
        return $settings;
    }

    public function update(SettingUpdateRequest $request) {
        $update = $this->settingRepo->updateSetting($request);
        return response()->json($update);
    }

    public function vatValue(){
        $value = $this -> settingRepo -> getVat();
        return response() -> json(['vat' => $value]);
    }
}
