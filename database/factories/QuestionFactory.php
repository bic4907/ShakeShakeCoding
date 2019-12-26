<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\UserType;
use App\Question;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Question::class, function (Faker $faker) {
    return [
        'code' => Str::random(10),
        'title' => $faker->title,
        'description' => $faker->text
    ];
});
