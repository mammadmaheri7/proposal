<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Professor::class, function (Faker $faker) {
    $majors = \App\Major::all();
    $user = factory(App\User::class)->create([
        'role_id' => 4
    ]);

    return [
        'level'             =>  $faker->randomElement(['استادتمام','استادیار']),
        'major_id'          =>  $majors[$faker->numberBetween(0,$majors->count()-1)]->id,
        'degree'            =>  $faker->randomElement(['Doctor','Bachelor']),
        'user_id'           =>  $user->id,
    ];
});
