<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserLogin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	'role_user' => 'Owner',
        	'name' => 'Admin',
        	'username' => 'admin',
        	'password' => bcrypt('admin'),

        ]);
    }
}
