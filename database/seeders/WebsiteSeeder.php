<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['github', 'stack', 'binance', 'reddit'];
        for($i = 0; $i < 3; $i++) {
            DB::table('websites')->insert([
                'name' => $names[$i],
                'url' => 'www.'.$names[$i].'.com',
            ]);
        }
    }
}
