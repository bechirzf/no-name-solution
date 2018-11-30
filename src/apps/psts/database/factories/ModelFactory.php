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
$factory->define(App\Models\Position::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->paragraph(1)
    ];
});
$factory->define(App\Models\Office::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'code' => $faker->name,
        'address' => $faker->address,
    ];
});
$factory->define(App\Models\Department::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph(1)
    ];
});
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'employee_id' => uniqid(1+strtotime('+60 days')),
        'email'    => $faker->unique()->email,
        'username'    => $faker->unique()->username,
        'password' => app('hash')->make('rex0521'),
        'office_id' => function() {
            return factory(App\Models\Office::class)->create()->id;
        },
        'position_id' => function() {
            return factory(App\Models\Position::class)->create()->id;
        },
        'department_id' => function() {
            return factory(App\Models\Department::class)->create()->id;
        },
    ];
});



