<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Lecture::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->text('10'),
        'description' => $faker->text('30'),
    ];
});

$factory->define(App\Lesson::class, function (Faker\Generator $faker) {
    return [
        'lesson_date' => $faker->date(),
        'lesson_time' => $faker->time(),
        'enabled' => $faker->boolean(),
    ];
});

$factory->define(App\Bookmark::class, function (Faker\Generator $faker) {
    return [
        'bookmark' => $faker->text(10),
        'created_at' => $faker->dateTimeBetween('-1 hour','now')
    ];
});

$factory->define(App\Rating::class, function (Faker\Generator $faker) {
    return [
        'session_id' => $faker->numberBetween(0,60),
        'lesson_id' => $faker->numberBetween(0,60),
        'rating' => $faker->numberBetween(1,5),
        'created_at' => $faker->dateTimeBetween('-1 hour','now')
    ];
});
