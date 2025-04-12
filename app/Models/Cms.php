<?php

namespace App\Models;

use App\Enums\CmsType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cms extends Model
{
    use HasFactory;

    protected $table = 'cms';

    protected $guarded = ['id'];
    protected $casts = [
        'type' => CmsType::class,
    ];
}
