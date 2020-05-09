<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;

$factory->define(\App\Student::class, function (Faker $faker) {
    $majors = \App\Major::all();
    $user = factory(App\User::class)->create([
        'role_id' => 2
    ]);


    return [
        'student_number'    =>  $faker->phoneNumber,
        'beginning_year'    =>  $faker->date(),
        'type'              =>  $faker->randomElement(['roozane','shabane','pardis']),
        'major_id'          =>  $majors[$faker->numberBetween(0,$majors->count()-1)]->id,
        'grade'             =>  $faker->randomElement(['BA','MA']),
        'user_id'           =>  $user->id,

    ];
});
