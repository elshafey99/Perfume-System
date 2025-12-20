<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'site_name'       => [
                'en' => 'Education Academy',
                'ar' => 'منصة تعليمية',
            ],
            'site_desc'       => [
                'en' => "Discover the finest selection of fresh meats with just a few taps. Our online store brings you top-quality cuts, customized to your preference, and delivered right to your doorstep. Enjoy a smooth, secure shopping experience, competitive prices, and unmatched service tailored to your needs.",
                'ar' => 'استمتع بأفضل تشكيلة من اللحوم الطازجة بضغطة زر. نوفر لك أجود أنواع اللحوم مع إمكانية التقطيع حسب رغبتك وتوصيلها حتى باب منزلك. تجربة تسوق سلسة وآمنة، وأسعار منافسة، وخدمة استثنائية مصممة لتلبية احتياجاتك.',
            ],

            'site_phone'      => '+123456789',
            'site_address'    => [
                'en' => 'Address in English',
                'ar' => 'العنوان بالعربية',
            ],
            'about_us'    => [
                'en' => 'About in English',
                'ar' => 'من نحن بالعربية',
            ],
            'site_email'      => 'info@mywebsite.com',
            'email_support'   => 'support@mywebsite.com',
            'facebook'        => 'https://facebook.com/',
            'x_url'           => 'https://x.com',
            'youtube'         => 'https://youtube.com/',
            'meta_desc'       => [
                'en' => "Discover the finest selection of fresh meats with just a few taps. Our online store brings you top-quality cuts, customized to your preference, and delivered right to your doorstep. Enjoy a smooth, secure shopping experience, competitive prices, and unmatched service tailored to your needs.",
                'ar' => 'استمتع بأفضل تشكيلة من اللحوم الطازجة بضغطة زر. نوفر لك أجود أنواع اللحوم مع إمكانية التقطيع حسب رغبتك وتوصيلها حتى باب منزلك. تجربة تسوق سلسة وآمنة، وأسعار منافسة، وخدمة استثنائية مصممة لتلبية احتياجاتك.',
            ],
            'logo'            => 'uploads/images/logo.png',
            'favicon'         => 'uploads/images/logo.png',
            'site_copyright'  => '© 2025 My Website. All rights reserved.',
            'promotion_url'   => 'https://mywebsite.com/promotion',

        ]);
    }
}
