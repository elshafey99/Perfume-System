<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'name' => 'شركة العطور الفرنسية',
            'contact_person' => 'أحمد محمد',
            'phone' => '+201234567890',
            'email' => 'contact@french-perfumes.com',
            'address' => '123 شارع العطور، القاهرة، مصر',
            'tax_number' => '12345678901234',
            'notes' => 'مورد رئيسي للعطور الفرنسية الأصلية',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'مصنع الزيوت العطرية',
            'contact_person' => 'محمد علي',
            'phone' => '+201987654321',
            'email' => 'info@aromatic-oils.com',
            'address' => '456 طريق الصناعة، الإسكندرية، مصر',
            'tax_number' => '98765432109876',
            'notes' => 'متخصص في الزيوت العطرية الخام',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'شركة الكحول الصناعية',
            'contact_person' => 'سارة أحمد',
            'phone' => '+201112233445',
            'email' => 'sales@industrial-alcohol.com',
            'address' => '789 منطقة الصناعات، الجيزة، مصر',
            'tax_number' => '11223344556677',
            'notes' => 'مورد الكحول عالي الجودة',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'مصنع الزجاجات والعبوات',
            'contact_person' => 'خالد حسن',
            'phone' => '+201556677889',
            'email' => 'orders@bottles-packaging.com',
            'address' => '321 شارع التصنيع، المنصورة، مصر',
            'tax_number' => '55667788990011',
            'notes' => 'زجاجات فارغة بجميع الأحجام',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'شركة مواد التغليف',
            'contact_person' => 'فاطمة إبراهيم',
            'phone' => '+201998877665',
            'email' => 'info@packaging-materials.com',
            'address' => '654 شارع التجارة، طنطا، مصر',
            'tax_number' => '99887766554433',
            'notes' => 'مواد تغليف فاخرة للعطور',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'مورد المثبتات الكيميائية',
            'contact_person' => 'علي محمود',
            'phone' => '+201223344556',
            'email' => 'contact@fixatives-supplier.com',
            'address' => '987 منطقة الكيماويات، القاهرة، مصر',
            'tax_number' => '22334455667788',
            'notes' => 'مثبتات عطرية بجودة عالية',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'شركة العود الأصيل',
            'contact_person' => 'يوسف عبدالله',
            'phone' => '+201334455667',
            'email' => 'sales@authentic-oud.com',
            'address' => '147 شارع العود، دمياط، مصر',
            'tax_number' => '33445566778899',
            'notes' => 'متخصص في زيوت العود الأصيلة',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'مورد المستلزمات العامة',
            'contact_person' => 'نورا سعيد',
            'phone' => '+201445566778',
            'email' => 'info@general-supplies.com',
            'address' => '258 شارع الموردين، أسيوط، مصر',
            'tax_number' => '44556677889900',
            'notes' => 'مستلزمات وإكسسوارات متنوعة',
            'is_active' => false,
        ]);
    }
}

