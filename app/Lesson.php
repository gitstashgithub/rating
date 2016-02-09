<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description','lesson_date', 'lesson_time', 'enabled'
    ];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
