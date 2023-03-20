<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Admin();
        $admin->first_name = 'Admin';
        $admin->last_name = 'Admin';
        $admin->email = 'admin@jambolo.com';
        $admin->email_verified_at = now();
        $admin->password = Hash::make('password'); // password
        $admin->save();
    }
}
