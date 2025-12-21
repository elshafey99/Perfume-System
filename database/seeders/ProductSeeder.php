<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductType;
use App\Models\UnitType;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get references to existing data
        $categories = Category::all()->keyBy('name');
        $productTypes = ProductType::all()->keyBy('code');
        $unitTypes = UnitType::all()->keyBy('code');
        $suppliers = Supplier::all();

        // Ready-made perfumes (عطور جاهزة)
        $readyMadeCategory = $categories->get('عطور جاهزة');
        $readyMadeType = $productTypes->get('ready_made');
        $pieceUnit = $unitTypes->get('piece');
        $mlUnit = $unitTypes->get('ml');

        if ($readyMadeCategory && $readyMadeType && $pieceUnit) {
            Product::create([
                'name' => 'عطر شانيل رقم 5',
                'sku' => 'PERF-001',
                'barcode' => '1234567890123',
                'category_id' => $readyMadeCategory->id,
                'product_type_id' => $readyMadeType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 25.0000,
                'min_stock_level' => 5.0000,
                'max_stock_level' => 50.0000,
                'cost_price' => 450.00,
                'selling_price' => 750.00,
                'price_per_ml' => 15.00,
                'image' => null,
                'description' => 'عطر نسائي فاخر من شانيل، 50 مل',
                'brand' => 'شانيل',
                'is_raw_material' => false,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => true,
                'supplier_id' => $suppliers->where('name', 'شركة العطور الفرنسية')->first()?->id,
            ]);

            Product::create([
                'name' => 'عطر دايور سوفاج',
                'sku' => 'PERF-002',
                'barcode' => '1234567890124',
                'category_id' => $readyMadeCategory->id,
                'product_type_id' => $readyMadeType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 18.0000,
                'min_stock_level' => 5.0000,
                'max_stock_level' => 40.0000,
                'cost_price' => 380.00,
                'selling_price' => 650.00,
                'price_per_ml' => 13.00,
                'image' => null,
                'description' => 'عطر رجالي قوي من دايور، 50 مل',
                'brand' => 'ديور',
                'is_raw_material' => false,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => true,
                'supplier_id' => $suppliers->where('name', 'شركة العطور الفرنسية')->first()?->id,
            ]);

            Product::create([
                'name' => 'عطر توم فورد بلاك أوركيد',
                'sku' => 'PERF-003',
                'barcode' => '1234567890125',
                'category_id' => $readyMadeCategory->id,
                'product_type_id' => $readyMadeType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 12.0000,
                'min_stock_level' => 3.0000,
                'max_stock_level' => 30.0000,
                'cost_price' => 550.00,
                'selling_price' => 950.00,
                'price_per_ml' => 19.00,
                'image' => null,
                'description' => 'عطر فاخر من توم فورد، 50 مل',
                'brand' => 'توم فورد',
                'is_raw_material' => false,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => true,
                'supplier_id' => $suppliers->where('name', 'شركة العطور الفرنسية')->first()?->id,
            ]);
        }

        // Raw oils (زيوت عطرية خام)
        $oilsCategory = $categories->get('زيوت عطرية خام');
        $rawOilType = $productTypes->get('raw_oil');
        $gramUnit = $unitTypes->get('gram');
        $tolaUnit = $unitTypes->get('tola');
        $quarterTolaUnit = $unitTypes->get('quarter_tola');

        if ($oilsCategory && $rawOilType && $gramUnit) {
            Product::create([
                'name' => 'زيت عود هندي أصيل',
                'sku' => 'OIL-001',
                'barcode' => '2234567890123',
                'category_id' => $oilsCategory->id,
                'product_type_id' => $rawOilType->id,
                'unit_type_id' => $gramUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 500.0000,
                'min_stock_level' => 100.0000,
                'max_stock_level' => 1000.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_gram' => 25.00,
                'image' => null,
                'description' => 'زيت عود هندي أصيل عالي الجودة',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'شركة العود الأصيل')->first()?->id,
            ]);

            Product::create([
                'name' => 'زيت ورد دمشقي',
                'sku' => 'OIL-002',
                'barcode' => '2234567890124',
                'category_id' => $oilsCategory->id,
                'product_type_id' => $rawOilType->id,
                'unit_type_id' => $gramUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 300.0000,
                'min_stock_level' => 50.0000,
                'max_stock_level' => 800.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_gram' => 15.00,
                'image' => null,
                'description' => 'زيت ورد دمشقي نقي',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مصنع الزيوت العطرية')->first()?->id,
            ]);

            Product::create([
                'name' => 'زيت ياسمين',
                'sku' => 'OIL-003',
                'barcode' => '2234567890125',
                'category_id' => $oilsCategory->id,
                'product_type_id' => $rawOilType->id,
                'unit_type_id' => $gramUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 400.0000,
                'min_stock_level' => 80.0000,
                'max_stock_level' => 900.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_gram' => 12.00,
                'image' => null,
                'description' => 'زيت ياسمين عطري',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مصنع الزيوت العطرية')->first()?->id,
            ]);

            Product::create([
                'name' => 'زيت عود كمبودي',
                'sku' => 'OIL-004',
                'barcode' => '2234567890126',
                'category_id' => $oilsCategory->id,
                'product_type_id' => $rawOilType->id,
                'unit_type_id' => $tolaUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 20.0000,
                'min_stock_level' => 5.0000,
                'max_stock_level' => 50.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_gram' => 30.00,
                'image' => null,
                'description' => 'زيت عود كمبودي فاخر',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'شركة العود الأصيل')->first()?->id,
            ]);
        }

        // Alcohol (كحول)
        $alcoholCategory = $categories->get('كحول');
        $alcoholType = $productTypes->get('alcohol');

        if ($alcoholCategory && $alcoholType && $mlUnit) {
            Product::create([
                'name' => 'كحول إيثيلي 96%',
                'sku' => 'ALC-001',
                'barcode' => '3234567890123',
                'category_id' => $alcoholCategory->id,
                'product_type_id' => $alcoholType->id,
                'unit_type_id' => $mlUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 5000.0000,
                'min_stock_level' => 1000.0000,
                'max_stock_level' => 10000.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_ml' => 0.15,
                'image' => null,
                'description' => 'كحول إيثيلي عالي الجودة 96%',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'شركة الكحول الصناعية')->first()?->id,
            ]);

            Product::create([
                'name' => 'كحول إيثيلي 70%',
                'sku' => 'ALC-002',
                'barcode' => '3234567890124',
                'category_id' => $alcoholCategory->id,
                'product_type_id' => $alcoholType->id,
                'unit_type_id' => $mlUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 3000.0000,
                'min_stock_level' => 500.0000,
                'max_stock_level' => 8000.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_ml' => 0.12,
                'image' => null,
                'description' => 'كحول إيثيلي 70%',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'شركة الكحول الصناعية')->first()?->id,
            ]);
        }

        // Bottles (زجاجات فارغة)
        $bottlesCategory = $categories->get('زجاجات فارغة');
        $bottleType = $productTypes->get('bottle');

        if ($bottlesCategory && $bottleType && $pieceUnit) {
            Product::create([
                'name' => 'زجاجة عطر 50 مل',
                'sku' => 'BTL-001',
                'barcode' => '4234567890123',
                'category_id' => $bottlesCategory->id,
                'product_type_id' => $bottleType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 200.0000,
                'min_stock_level' => 50.0000,
                'max_stock_level' => 500.0000,
                'cost_price' => 8.00,
                'selling_price' => 12.00,
                'image' => null,
                'description' => 'زجاجة عطر زجاجية بسعة 50 مل',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مصنع الزجاجات والعبوات')->first()?->id,
            ]);

            Product::create([
                'name' => 'زجاجة عطر 100 مل',
                'sku' => 'BTL-002',
                'barcode' => '4234567890124',
                'category_id' => $bottlesCategory->id,
                'product_type_id' => $bottleType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 150.0000,
                'min_stock_level' => 30.0000,
                'max_stock_level' => 400.0000,
                'cost_price' => 12.00,
                'selling_price' => 18.00,
                'image' => null,
                'description' => 'زجاجة عطر زجاجية بسعة 100 مل',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مصنع الزجاجات والعبوات')->first()?->id,
            ]);

            Product::create([
                'name' => 'زجاجة عطر 30 مل',
                'sku' => 'BTL-003',
                'barcode' => '4234567890125',
                'category_id' => $bottlesCategory->id,
                'product_type_id' => $bottleType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 180.0000,
                'min_stock_level' => 40.0000,
                'max_stock_level' => 450.0000,
                'cost_price' => 6.00,
                'selling_price' => 10.00,
                'image' => null,
                'description' => 'زجاجة عطر زجاجية بسعة 30 مل',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مصنع الزجاجات والعبوات')->first()?->id,
            ]);
        }

        // Packaging (تغليف)
        $packagingCategory = $categories->get('تغليف');
        $packagingType = $productTypes->get('packaging');

        if ($packagingCategory && $packagingType && $pieceUnit) {
            Product::create([
                'name' => 'صندوق هدايا فاخر',
                'sku' => 'PKG-001',
                'barcode' => '5234567890123',
                'category_id' => $packagingCategory->id,
                'product_type_id' => $packagingType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 100.0000,
                'min_stock_level' => 20.0000,
                'max_stock_level' => 300.0000,
                'cost_price' => 15.00,
                'selling_price' => 25.00,
                'image' => null,
                'description' => 'صندوق هدايا فاخر للعطور',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'شركة مواد التغليف')->first()?->id,
            ]);

            Product::create([
                'name' => 'كيس هدايا حريري',
                'sku' => 'PKG-002',
                'barcode' => '5234567890124',
                'category_id' => $packagingCategory->id,
                'product_type_id' => $packagingType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 250.0000,
                'min_stock_level' => 50.0000,
                'max_stock_level' => 600.0000,
                'cost_price' => 3.00,
                'selling_price' => 5.00,
                'image' => null,
                'description' => 'كيس هدايا حريري أنيق',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'شركة مواد التغليف')->first()?->id,
            ]);
        }

        // Fixatives (مثبتات)
        $fixativesCategory = $categories->get('مثبتات');
        $fixativeType = $productTypes->get('fixative');

        if ($fixativesCategory && $fixativeType && $gramUnit) {
            Product::create([
                'name' => 'مثبت عطري بندريت',
                'sku' => 'FIX-001',
                'barcode' => '6234567890123',
                'category_id' => $fixativesCategory->id,
                'product_type_id' => $fixativeType->id,
                'unit_type_id' => $gramUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 1000.0000,
                'min_stock_level' => 200.0000,
                'max_stock_level' => 2000.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_gram' => 0.50,
                'image' => null,
                'description' => 'مثبت عطري بندريت عالي الجودة',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مورد المثبتات الكيميائية')->first()?->id,
            ]);

            Product::create([
                'name' => 'مثبت عطري إيزو إي سوبر',
                'sku' => 'FIX-002',
                'barcode' => '6234567890124',
                'category_id' => $fixativesCategory->id,
                'product_type_id' => $fixativeType->id,
                'unit_type_id' => $gramUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 800.0000,
                'min_stock_level' => 150.0000,
                'max_stock_level' => 1800.0000,
                'cost_price' => 0.00,
                'selling_price' => 0.00,
                'price_per_gram' => 0.45,
                'image' => null,
                'description' => 'مثبت عطري إيزو إي سوبر',
                'brand' => null,
                'is_raw_material' => true,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مورد المثبتات الكيميائية')->first()?->id,
            ]);
        }

        // Accessories (مستلزمات)
        $accessoriesCategory = $categories->get('مستلزمات');
        $accessoryType = $productTypes->get('accessory');

        if ($accessoriesCategory && $accessoryType && $pieceUnit) {
            Product::create([
                'name' => 'بخاخ عطر',
                'sku' => 'ACC-001',
                'barcode' => '7234567890123',
                'category_id' => $accessoriesCategory->id,
                'product_type_id' => $accessoryType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 120.0000,
                'min_stock_level' => 30.0000,
                'max_stock_level' => 300.0000,
                'cost_price' => 2.00,
                'selling_price' => 4.00,
                'image' => null,
                'description' => 'بخاخ عطر بلاستيكي',
                'brand' => null,
                'is_raw_material' => false,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => true,
                'supplier_id' => $suppliers->where('name', 'مورد المستلزمات العامة')->first()?->id,
            ]);

            Product::create([
                'name' => 'ملصق عطر',
                'sku' => 'ACC-002',
                'barcode' => '7234567890124',
                'category_id' => $accessoriesCategory->id,
                'product_type_id' => $accessoryType->id,
                'unit_type_id' => $pieceUnit->id,
                'conversion_rate' => 1.0000,
                'current_stock' => 500.0000,
                'min_stock_level' => 100.0000,
                'max_stock_level' => 1000.0000,
                'cost_price' => 0.50,
                'selling_price' => 1.00,
                'image' => null,
                'description' => 'ملصق عطر مخصص',
                'brand' => null,
                'is_raw_material' => false,
                'is_composition' => false,
                'is_active' => true,
                'can_return' => false,
                'supplier_id' => $suppliers->where('name', 'مورد المستلزمات العامة')->first()?->id,
            ]);
        }
    }
}

