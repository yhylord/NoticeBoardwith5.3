<?php

$factory->define(App\Vote::class, function (Faker\Generator $faker) {
    $sentence = $faker->sentence($faker->randomDigit);
    $title = substr($sentence, 0, strlen($sentence) - 1); // remove the trailing period

    return [
        'title' => $title,
        'user_id' => App\User::inRandomOrder()->first()->id,
        'intro' => $faker->paragraph,
        'end_word' => $faker->sentence,
        'type' => '2',
        'started_at' => $faker->dateTime,
        'ended_at' => $faker->dateTimeBetween('+0 days', '+2 years')
    ];
});