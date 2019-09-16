<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Settings\Category::class, function (Faker $faker) {
    return [
        'slug' => str_slug($faker->name, '-'),
        'name' => $faker->name
    ];
});
