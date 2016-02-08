<?php

namespace App\Http\Controllers;

use App\Lecture;
use App\Lesson;
use App\Rating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Ramsey\Uuid\Uuid;

class LessonController extends Controller
{
    public function create($lectureId)
    {
        $lesson = new Lesson;
        $lecture = Lecture::find($lectureId);

        $action = array('LessonController@store');

        return response()
            ->view('lesson.edit', ['lesson' => $lesson, 'action' => $action, 'method' => 'POST', 'lecture' => $lecture]);
    }

    public function edit($id)
    {
        $lesson = Lesson::find($id);
        $lecture = $lesson->lecture;

        $action = array('LessonController@update', $lesson->id);

        return response()
            ->view('lesson.edit', ['lesson' => $lesson, 'action' => $action, 'method' => 'PUT', 'lecture' => $lecture]);
    }

    public function store(Request $request)
    {
        $lesson = new Lesson();
        $lesson->lecture_id = $request->get('lectureId');
        $lesson->lesson_date = $request->get('lesson_date');
        $lesson->lesson_time = $request->get('lesson_time');
        $lesson->enabled = 0;
        $lesson->save();
        return redirect('lecture/' . $lesson->lecture_id . '/lesson/');
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response('Leeson not found', 404);
        }
        $lesson->lesson_date = $request->get('lesson_date');
        $lesson->lesson_time = $request->get('lesson_time');
        $lesson->save();
        return redirect('lecture/' . $lesson->lecture_id . '/lesson/');
    }

    public function show($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response('Leeson not found', 404);
        }
        if($lesson->enabled){
            $length = 90;   //minute
            $now = new Carbon();
            if($lesson->updated_at->diffInMinutes($now) > $length){
                $lesson->enabled = 0;
                $lesson->save();
            }
        }
        return response()
            ->view('lesson.show', ['lesson' => $lesson]);
    }

    public function all($id)
    {
        $lecture = Lecture::find($id);
        $lessons = $lecture->lessons()->get();
//        dd($lecture,$lessons);
        return response()
            ->view('lesson.all', ['lessons' => $lessons, 'lecture' => $lecture]);
    }

    public function enable($id)
    {
        $lesson = Lesson::find($id);
        if ($lesson->enabled) {
            $lesson->enabled = false;
            $lesson->save();
        } else {
            $lesson->lecture->lessons()->get()->each(function (Lesson $lesson) {
                $lesson->enabled = false;
                $lesson->save();
            });
            $lesson->enabled = true;
            $lesson->save();
        }
    }
}
