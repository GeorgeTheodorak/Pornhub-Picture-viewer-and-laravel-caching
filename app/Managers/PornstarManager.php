<?php

namespace App\Managers;

use App\Models\Pornstar;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PornstarManager
{
    private array $pornstarsToSave = [];

    public function __construct()
    {
    }

    public function entryPoint(array $pornstarData): void
    {
        $pornstarModel = $this->prepareModel($pornstarData);
        $this->pornstarsToSave[] = $pornstarModel;
    }

    private function prepareModel(array $data): Pornstar
    {
        // Try to find an existing Pornstar model by the unique 'pornhub_id'
        $pornstarModel = Pornstar::where('pornhub_id', $data['id'])->first() ?? new Pornstar();

        $modelData = [
            'name' => $data['name'],
            'license' => $data['license'],
            'wl_status' => $data['wlStatus'],
            'aliases' => $data['aliases'],
            'link' => $data['link'],
            'pornhub_id' => $data['id'],
        ];

        if (isset($data['attributes'])) {
            $attributes = $data['attributes'];
            $modelData = array_merge($modelData, [
                'hair_color' => Arr::get($attributes, 'hairColor'),
                'ethnicity' => Arr::get($attributes, 'ethnicity'),
                'tattoos' => Arr::get($attributes, 'tattoos', false),
                'piercings' => Arr::get($attributes, 'piercings', false),
                'breast_size' => Arr::get($attributes, 'breastSize'),
                'breast_type' => Arr::get($attributes, 'breastType'),
                'gender' => Arr::get($attributes, 'gender'),
                'orientation' => Arr::get($attributes, 'orientation'),
                'age' => Arr::get($attributes, 'age'),
            ]);

            if (isset($attributes['stats'])) {
                $stats = $attributes['stats'];
                $modelData = array_merge($modelData, [
                    'subscriptions' => Arr::get($stats, 'subscriptions'),
                    'monthly_searches' => Arr::get($stats, 'monthlySearches'),
                    'views' => Arr::get($stats, 'views'),
                    'videos_count' => Arr::get($stats, 'videosCount'),
                    'premium_videos_count' => Arr::get($stats, 'premiumVideosCount'),
                    'white_label_video_count' => Arr::get($stats, 'whiteLabelVideoCount'),
                    'rank' => Arr::get($stats, 'rank'),
                    'rank_premium' => Arr::get($stats, 'rankPremium'),
                    'rank_wl' => Arr::get($stats, 'rankWl'),
                ]);
            }
        }

        if (isset($data['thumbnails'])) {
            $modelData['thumbnails'] = array_map(function($thumbnail) {
                return [
                    'height' => $thumbnail['height'],
                    'width' => $thumbnail['width'],
                    'type' => $thumbnail['type'],
                    'urls' => $thumbnail['urls'],
                ];
            }, $data['thumbnails']);
        }

        // Update or set attributes to the model
        $pornstarModel->fill($modelData);

        return $pornstarModel;
    }

    public function commitChanges(): void
    {
        DB::transaction(function () {
            foreach ($this->pornstarsToSave as $pornstarModel) {
                $pornstarModel->save();
            }
            $this->pornstarsToSave = []; // Clear the array after saving
        });
    }
}
