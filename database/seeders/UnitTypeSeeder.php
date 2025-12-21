<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitTypes = [
            [
                'code' => 'piece',
                'name' => 'قطعة',
                'symbol' => 'قطعة',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'gram',
                'name' => 'جرام',
                'symbol' => 'جم',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'ml',
                'name' => 'مليلتر',
                'symbol' => 'مل',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'tola',
                'name' => 'تولة',
                'symbol' => 'تولة',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'quarter_tola',
                'name' => 'ربع تولة',
                'symbol' => 'ربع تولة',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($unitTypes as $type) {
            UnitType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
