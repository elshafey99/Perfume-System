<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;
    public $table = 'settings';
    public $translatable = [
        'site_name',
        'site_desc',
        'site_address',
        'meta_desc',
        'about_us',
    ];

    protected $fillable = [
        'site_name',
        'site_desc',
        'site_phone',
        'site_address',
        'site_email',
        'email_support',
        'facebook',
        'x_url',
        'youtube',
        'meta_desc',
        'logo',
        'favicon',
        'site_copyright',
        'promotion_url',
        'about_us',
    ];
}
