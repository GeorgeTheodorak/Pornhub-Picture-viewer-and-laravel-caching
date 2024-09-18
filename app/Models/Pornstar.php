<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pornstar extends Model
{
    use HasFactory;

    protected $fillable = [
        'pornhub_id',
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
        'pornhub_id' => 'integer',
        'tattoos' => 'boolean',
        'piercings' => 'boolean',
        'age' => 'integer',
        'subscriptions' => 'integer',
        'monthly_searches' => 'integer',
        'views' => 'integer',
        'videos_count' => 'integer',
        'premium_videos_count' => 'integer',
        'white_label_video_count' => 'integer',
        'rank' => 'integer',
        'rank_premium' => 'integer',
        'rank_wl' => 'integer',
        'aliases' => 'array',
        'thumbnails' => 'array',
    ];

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function scopeWithTattoos($query)
    {
        return $query->where('tattoos', true);
    }

    public function scopeWithPiercings($query)
    {
        return $query->where('piercings', true);
    }

    public function scopeByEthnicity($query, $ethnicity)
    {
        return $query->where('ethnicity', $ethnicity);
    }

    public function scopeByHairColor($query, $hairColor)
    {
        return $query->where('hair_color', $hairColor);
    }

    public function scopeByAge($query, $minAge, $maxAge)
    {
        return $query->whereBetween('age', [$minAge, $maxAge]);
    }

    public function scopeTopRanked($query, $limit = 10)
    {
        return $query->orderBy('rank')->limit($limit);
    }

}
