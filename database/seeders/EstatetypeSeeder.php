<?php

namespace Database\Seeders;

use App\Models\EstateType;
use Illuminate\Database\Seeder;

class EstatetypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstateType::insert([
            ['name' => 'rent'],
            ['name' => 'sale'],
            ['name' => 'short-let']
        ]);
    }
}
