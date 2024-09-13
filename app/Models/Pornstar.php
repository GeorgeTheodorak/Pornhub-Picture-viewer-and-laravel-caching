<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pornstar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hair_color',
        'ethnicity',
        'tattoos',
        'piercings',
        'breast_size',
        'breast_type',
        'gender',
        'orientation',
        'age',
        'subscriptions',
        'monthly_searches',
        'views',
        'videos_count',
        'premium_videos_count',
        'white_label_video_count',
        'rank',
        'rank_premium',
        'rank_wl',
        'license',
        'wl_status',
        'aliases',
        'link',
        'thumbnails',
    ];

    protected $casts = [
        'aliases' => 'array',
        'thumbnails' => 'array',
    ];
}
