<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Settings\Supplier::class, function (Faker $faker) {
    return [
        'code' => str_random(10),
        'name' => $faker->company,
        'mobile' => $faker->e164PhoneNumber,
        'address' => $faker->address,
        'details' => $faker->text,
    ];
});
