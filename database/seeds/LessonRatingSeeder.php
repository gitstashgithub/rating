<?php

use App\Bookmark;
use App\Lecture;
use App\Lesson;
use App\Rating;
use Illuminate\Database\Seeder;

class LessonRatingSeeder extends Seeder
{
    public function run()
    {
        factory(Lecture::class, 2)->create()->each(function (Lecture $lecture) {
            factory(Lesson::class, 2)
                ->create(['lecture_id' => $lecture->id])
                ->each(function (Lesson $lesson) {
                    for ($i = 0; $i < 5; $i++) {
                        factory(Rating::class, 5)->create(['session_id' => $i, 'lesson_id' => $lesson->id]);
                        factory(Bookmark::class, 5)->create(['lesson_id' => $lesson->id]);
                    }
                });
        });
    }
}