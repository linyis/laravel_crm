<?php

use Faker\Generator as Faker;
use App\User;
use App\Crm;
use Illuminate\Support\Facades\Hash;

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('111111'), // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Crm::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'subject' => $faker->title(10),
        'content' => $faker->realText(50)
    ];
});


// $factory->define(App\Category::class, function (Faker $faker) {
//     return [
//         ['name' => 'China',
//             'children' => [
//                 ['name' => 'Zhejiang',
//                     'children' => [
//                         ['name' => 'Ningbo'],
//                         ['name' => 'Huzhou']
//                     ]],
//                 ['name' => 'Jiangsu']
//             ]],
//     ];
// });



