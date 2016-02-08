<?php

namespace App\Http\Controllers;

use App\Lecture;

class LectureController extends Controller
{
    public function show($id){
        $lecture = Lecture::find($id);
        if(!$lecture){
            return response('Lecture not found', 404);
        }
        return response()
            ->view('lecture.show',['lecture'=>$lecture]);
    }

    public function all(){
        $lectures = Lecture::all();
        return response()
            ->view('lecture.all',['lectures'=>$lectures]);

    }
}
