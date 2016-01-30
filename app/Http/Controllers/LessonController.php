<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class LessonController extends Controller
{
    public function show($id){
        $lesson = Lesson::find($id);
        if(!$lesson){
            return response('Leeson not found', 404);
        }
        return response()
            ->view('lesson.show',['lesson'=>$lesson]);
    }

    public function all(){
        $lessons = Lesson::all();
        return response()
            ->view('lesson.all',['lessons'=>$lessons]);

    }
}
