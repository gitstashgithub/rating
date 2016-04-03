<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function all()
    {
        $settings = Setting::all();
        return response()
            ->view('setting.all', ['settings' => $settings]);
    }

    public function store(Request $request)
    {
        foreach (Input::except('_token') as $id=>$value) {
            $setting = Setting::find($id);
            $setting->value = $value;
            $setting->save();
        }
        return redirect()->route("settings.all")->with('setting-saved', 'Successful saved!');
    }
}
