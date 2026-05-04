<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'setting_group',
        'label',
        'sort_order',
        'is_public',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_public' => 'boolean',
    ];
}
