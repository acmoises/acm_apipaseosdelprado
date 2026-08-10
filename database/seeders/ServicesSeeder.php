<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Serv. Plumas Auto',
			'Serv. Plumas Moto',
			'Serv. Plumas Taxi',
			'Residente',
			'Parcial',
			'Donacion'
        ];

        foreach ($names as $name) {
            Service::create([
                'name' => $name,
            ]);
        }
    }
}
