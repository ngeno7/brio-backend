<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use IlluminateAgnostic\Str\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Brio',
            'email' => env('BRIO_ADMIN'),
            'password' => Hash::make(env('ADMN_PWD')),
            'token' => Str::random(80),
        ]);
    }
}
