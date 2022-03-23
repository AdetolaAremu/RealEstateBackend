<?php

namespace Database\Seeders;

use App\Models\EstateType;
use Carbon\Carbon;
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
        EstateType::insert(array(
            0 => array('id' => 1,'name' => 'rent'),
            1 => array('id' => 2, 'name' => 'sale'),
            2 => array('id' => 3, 'name' => 'short-let')
        ));
    }
}
