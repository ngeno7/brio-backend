<?php

namespace Database\Seeders;

use App\Models\GlobalKpi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use IlluminateAgnostic\Str\Support\Str;

class GlobalKpisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GlobalKpi::insert([
            [
                'slug' => Str::kebab('Cost Containment'),
                'name' => 'Cost Containment',
                'created_at' => Carbon::now(),
                'system'=>true,
            ],
            [
                'slug' => Str::kebab('Communication'),
                'name' => 'Communication',
                'created_at' => Carbon::now(),
                'system'=>true,
            ],
            [
                'slug' => Str::kebab('Lifestyle'),
                'name' => 'Lifestyle',
                'created_at' => Carbon::now(),
                'system'=>true,
            ],
            [
                'slug' => Str::kebab('Compliance'),
                'name' => 'Compliance',
                'created_at' => Carbon::now(),
                'system'=>true,
            ],
            [
                'slug' => Str::kebab('Advocacy'),
                'name' => 'Advocacy',
                'created_at' => Carbon::now(),
                'system'=>true,
            ],
            [
                'slug' => Str::kebab('Technology'),
                'name' => 'Technology',
                'created_at' => Carbon::now(),
                'system'=>true,
            ],
        ]);
    }
}
