<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productTypes = [
            [
                'code' => 'ready_made',
                'name' => 'عطر جاهز',
                'description' => 'عطور جاهزة للبيع',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'raw_oil',
                'name' => 'زيت خام',
                'description' => 'زيوت عطرية خام',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'alcohol',
                'name' => 'كحول',
                'description' => 'كحول للعطور',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'bottle',
                'name' => 'زجاجة',
                'description' => 'زجاجات للعطور',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'code' => 'packaging',
                'name' => 'تغليف',
                'description' => 'مواد التغليف',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'code' => 'fixative',
                'name' => 'مثبت',
                'description' => 'مواد مثبتة للعطور',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'code' => 'accessory',
                'name' => 'إكسسوار',
                'description' => 'إكسسوارات للعطور',
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($productTypes as $type) {
            ProductType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
