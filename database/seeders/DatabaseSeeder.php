<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Creating users
        $this->createUser('owner', 'owner@gmail.com', 'owner');
        $this->createUser('admin', 'admin@gmail.com', 'admin');
        $this->createUser('user', 'user@gmail.com', 'user');

        // Creating categories
        $this->createCategory('Processor');
        $this->createCategory('Motherboard');
        $this->createCategory('RAM');
        $this->createCategory('VGA');
        $this->createCategory('Aksesoris');
        $this->createCategory('Laptop');

        $this->createSetting('08.00 - 17.00 WIB', 'BCA : Milenial Comp', '4628175431');
    }

    private function createUser($name, $email, $role)
    {
        User::create([
            'name'      => $name,
            'email'     => $email,
            'role'      => $role,
            'password'  => bcrypt('password'),
        ]);
    }
    private function createSetting($jam_buka, $nama_rekening, $nomor_rekening)
    {
        Setting::create([
            'jam_buka'  => $jam_buka,
            'nama_rekening'  => $nama_rekening,
            'nomor_rekening'  => $nomor_rekening,
        ]);
    }

    private function createCategory($name)
    {
        Category::create([
            'name' => $name,
        ]);
    }
}
