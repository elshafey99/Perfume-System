<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent categories
        $perfumes = Category::create([
            'name' => 'عطور جاهزة',
            'parent_id' => null,
            'icon' => 'perfume',
            'description' => 'العطور الجاهزة والمصنعة',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $oils = Category::create([
            'name' => 'زيوت عطرية خام',
            'parent_id' => null,
            'icon' => 'oil',
            'description' => 'الزيوت العطرية الخام المستخدمة في التركيبات',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $alcohol = Category::create([
            'name' => 'كحول',
            'parent_id' => null,
            'icon' => 'alcohol',
            'description' => 'الكحول المستخدم في صناعة العطور',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $bottles = Category::create([
            'name' => 'زجاجات فارغة',
            'parent_id' => null,
            'icon' => 'bottle',
            'description' => 'الزجاجات الفارغة للتعبئة',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $packaging = Category::create([
            'name' => 'تغليف',
            'parent_id' => null,
            'icon' => 'package',
            'description' => 'مواد التغليف والعبوات',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $fixatives = Category::create([
            'name' => 'مثبتات',
            'parent_id' => null,
            'icon' => 'fixative',
            'description' => 'المثبتات المستخدمة في العطور',
            'sort_order' => 6,
            'is_active' => true,
        ]);

        $accessories = Category::create([
            'name' => 'مستلزمات',
            'parent_id' => null,
            'icon' => 'accessories',
            'description' => 'المستلزمات والإكسسوارات',
            'sort_order' => 7,
            'is_active' => true,
        ]);

        // Create sub-categories for عطور جاهزة
        Category::create([
            'name' => 'عطور نسائية',
            'parent_id' => $perfumes->id,
            'icon' => 'perfume-female',
            'description' => 'عطور مخصصة للنساء',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'عطور رجالية',
            'parent_id' => $perfumes->id,
            'icon' => 'perfume-male',
            'description' => 'عطور مخصصة للرجال',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'عطور عائلية',
            'parent_id' => $perfumes->id,
            'icon' => 'perfume-unisex',
            'description' => 'عطور مناسبة للجميع',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Create sub-categories for زيوت عطرية خام
        Category::create([
            'name' => 'عطور خشبية',
            'parent_id' => $oils->id,
            'icon' => 'wood',
            'description' => 'زيوت عطرية خشبية',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'عطور حلوة',
            'parent_id' => $oils->id,
            'icon' => 'sweet',
            'description' => 'زيوت عطرية حلوة',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'عطور عود',
            'parent_id' => $oils->id,
            'icon' => 'oud',
            'description' => 'زيوت عطرية من العود',
            'sort_order' => 3,
            'is_active' => true,
        ]);
    }
}

