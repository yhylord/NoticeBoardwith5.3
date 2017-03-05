<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 2)->create()->each(function ($user) {
            factory(App\Vote::class, rand(0, 3))->create(['creator_id' => $user->id]);
        });
    }
}
