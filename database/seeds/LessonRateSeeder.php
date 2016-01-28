<?php

use App\Lesson;
use App\Rate;
use Illuminate\Database\Seeder;

class LessonRate extends Seeder
{
    public function run()
    {
        factory(Lesson::class, 2)
            ->create()
            ->each(function (Lesson $lesson) {
                for ($i = 0; $i < 30; $i++) {
                    factory(Rate::class, 30)->create(['session_id' => $i,'lesson_id'=>$lesson->id]);
                }
            });
    }
}