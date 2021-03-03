<?php

use Faker\Generator as Faker;

$factory->define(\App\Model\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'content' => $faker->sentence,
    ];
});
