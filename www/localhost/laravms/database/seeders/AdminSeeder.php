<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
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
        // php artisan db:seed --class=AdminSeeder
        $time = date('Y-m-d H:i:s');
        $data = [
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@laravms.com',
                'password' => Hash::make('laravms@2022'),
                'created_at' => $time,
                'updated_at' => $time
            ]
        ];
        Admin::insert($data);
    }
}
