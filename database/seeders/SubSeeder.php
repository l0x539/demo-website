<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::find(1);
        DB::table('subscriptions')->insert([
            'website_id' => 1,
            'email' => 'test@test.com',
        ]);

        $user2 = User::find(2);
        DB::table('subscriptions')->insert([
            'website_id' => 3,
            'email' => 'test2@test.com',
        ]);


    }
}
