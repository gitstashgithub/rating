<?php

namespace App\Http\Controllers;

use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class RateController extends Controller
{
    public function show(){
        return response()
            ->view('rate.result');
    }


    public function getResult(Request $request, $id)
    {
        $session_id = Session::getId();

        if(!$session_id){
            return response('',400);
        }

        $array = [];

        $results = DB
            ::table('rates')
            ->where('lesson_id','=',$id)
            ->orderBy('created_at')
            ->each(function($rate){
            var_dump($rate) ;
        });
//        foreach($result)
        //dd($results);
    }

    public function setRate(Request $request)
    {
        $session_id = Session::getId();

        if(!$session_id){
            return response('',400);
        }
        $this->validate($request, [
            'lesson_id' => 'required|integer',
            'rate' => 'required|integer',
        ]);

        $rate = new Rate();

        $rate->lesson_id = $request->lesson_id;
        $rate->rate = $request->rate;
        $rate->session_id = $session_id;

        $rate->save();

        return response('',201);
    }
}
