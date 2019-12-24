<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\UserType;
use App\Submission;
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

$factory->define(Submission::class, function (Faker $faker) {
    return [
        'answer' => Str::random(10),
        'isCorrect' => $faker->randomElement(array(true,false)),
    ];
});
