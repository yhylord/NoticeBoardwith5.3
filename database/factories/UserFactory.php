<?php

$chinese_faker = Faker\Factory::create('zh_CN');
$factory->define(App\User::class, function (Faker\Generator $faker) use ($chinese_faker) {
    return [
        'chinese_name' => $chinese_faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone_number' => $chinese_faker->phoneNumber,
        'password' => bcrypt('secret'),
        'grade' => $faker->year,
        'self_intro' => $faker->paragraph,
        'active' => '1'
    ];
});