<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Database\Factories\SystemSettingFactory;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
<<<<<<< HEAD
    /** @use HasFactory<\Database\Factories\SystemSettingFactory> */
=======
    /** @use HasFactory<SystemSettingFactory> */
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'label',
        'type',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }
}
