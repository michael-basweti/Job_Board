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
    ];
});

$factory->state(\App\User::class, 'employer', function (\Faker\Generator $faker) {
    return [
      'role' => 'employer',
    ];
  });

  $factory->state(\App\User::class, 'applicant', function (\Faker\Generator $faker) {
    return [
      'role' => 'applicant',
    ];
  });
