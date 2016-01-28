<?php

namespace App\Http\Controllers;

use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class LessonController extends Controller
{
    public function show($id){
        return response()
            ->view('lesson.show',['lesson_id'=>$id]);
    }

    public function getResult(Request $request)
    {
        //dd(Session::getId());


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
