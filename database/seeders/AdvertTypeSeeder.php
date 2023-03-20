<?php

namespace Database\Seeders;

use App\Models\AdvertType;
use Illuminate\Database\Seeder;

class AdvertTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Fixed',
                'description' => 'There will be a start date and end date that will run for a fixed date.'
            ],
            [
                'id' => 2,
                'name' => 'Pay per view',
                'description' => 'Advert expires if the number of views paid for has elapsed.'
            ],
        ];

        foreach ($items as $item) {
            AdvertType::updateOrCreate([
                'id' => $item['id'],
            ], $item);
        }
    }
}
