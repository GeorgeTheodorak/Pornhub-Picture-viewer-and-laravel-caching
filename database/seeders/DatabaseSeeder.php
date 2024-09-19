<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use http\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a new user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'), // Make sure to hash the password
        ]);

//        DB::table('pornstars')->insert([
//            [
//                'name' => 'Aaliyah Jolie',
//                'hair_color' => 'Blonde',
//                'ethnicity' => 'White',
//                'tattoos' => true,
//                'piercings' => true,
//                'breast_size' => 34,
//                'breast_type' => 'A',
//                'gender' => 'female',
//                'orientation' => 'straight',
//                'age' => 43,
//                'subscriptions' => 5717,
//                'monthly_searches' => 855400,
//                'views' => 460834,
//                'videos_count' => 54,
//                'premium_videos_count' => 27,
//                'white_label_video_count' => 42,
//                'rank' => 4328,
//                'rank_premium' => 4425,
//                'rank_wl' => 4066,
//                'license' => 'REGULAR',
//                'wl_status' => '1',
//                'aliases' => json_encode(['Aliyah Julie', 'Aliyah Jolie', 'Aaliyah', 'Macy']),
//                'link' => 'https://www.pornhub.com/pornstar/aaliyah-jolie',
//                'thumbnails' => json_encode([
//                    [
//                        'height' => 344,
//                        'width' => 234,
//                        'type' => 'pc',
//                        'urls' => ['https://ei.phncdn.com/pics/pornstars/000/000/002/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg']
//                    ],
//                    [
//                        'height' => 344,
//                        'width' => 234,
//                        'type' => 'mobile',
//                        'urls' => ['https://ei.phncdn.com/pics/pornstars/000/000/002/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg']
//                    ],
//                    [
//                        'height' => 344,
//                        'width' => 234,
//                        'type' => 'tablet',
//                        'urls' => ['https://ei.phncdn.com/pics/pornstars/000/000/002/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg']
//                    ]
//                ]),
//            ],
//            [
//                'name' => 'Jenna Jameson',
//                'hair_color' => 'Blonde',
//                'ethnicity' => 'White',
//                'tattoos' => true,
//                'piercings' => true,
//                'breast_size' => 36,
//                'breast_type' => 'B',
//                'gender' => 'female',
//                'orientation' => 'straight',
//                'age' => 49,
//                'subscriptions' => 7234,
//                'monthly_searches' => 1243000,
//                'views' => 970000,
//                'videos_count' => 62,
//                'premium_videos_count' => 32,
//                'white_label_video_count' => 20,
//                'rank' => 1001,
//                'rank_premium' => 1005,
//                'rank_wl' => 1200,
//                'license' => 'REGULAR',
//                'wl_status' => '1',
//                'aliases' => json_encode(['Jenna', 'Jenna Jameson']),
//                'link' => 'https://www.pornhub.com/pornstar/jenna-jameson',
//                'thumbnails' => json_encode([
//                    [
//                        'height' => 344,
//                        'width' => 234,
//                        'type' => 'pc',
//                        'urls' => ['https://example.com/jenna-thumbs.jpg']
//                    ]
//                ]),
//            ],
//            [
//                'name' => 'Riley Reid',
//                'hair_color' => 'Brunette',
//                'ethnicity' => 'Hispanic',
//                'tattoos' => false,
//                'piercings' => true,
//                'breast_size' => 32,
//                'breast_type' => 'A',
//                'gender' => 'female',
//                'orientation' => 'straight',
//                'age' => 28,
//                'subscriptions' => 8500,
//                'monthly_searches' => 1340000,
//                'views' => 580000,
//                'videos_count' => 78,
//                'premium_videos_count' => 44,
//                'white_label_video_count' => 15,
//                'rank' => 501,
//                'rank_premium' => 550,
//                'rank_wl' => 600,
//                'license' => 'REGULAR',
//                'wl_status' => '1',
//                'aliases' => json_encode(['Riley']),
//                'link' => 'https://www.pornhub.com/pornstar/riley-reid',
//                'thumbnails' => json_encode([
//                    [
//                        'height' => 344,
//                        'width' => 234,
//                        'type' => 'pc',
//                        'urls' => ['https://example.com/riley-thumbs.jpg']
//                    ]
//                ]),
//            ],
//            [
//                'name' => 'Mia Khalifa',
//                'hair_color' => 'Brunette',
//                'ethnicity' => 'Lebanese',
//                'tattoos' => false,
//                'piercings' => true,
//                'breast_size' => 34,
//                'breast_type' => 'B',
//                'gender' => 'female',
//                'orientation' => 'straight',
//                'age' => 31,
//                'subscriptions' => 10000,
//                'monthly_searches' => 2100000,
//                'views' => 1000000,
//                'videos_count' => 92,
//                'premium_videos_count' => 50,
//                'white_label_video_count' => 22,
//                'rank' => 200,
//                'rank_premium' => 250,
//                'rank_wl' => 300,
//                'license' => 'REGULAR',
//                'wl_status' => '1',
//                'aliases' => json_encode(['Mia']),
//                'link' => 'https://www.pornhub.com/pornstar/mia-khalifa',
//                'thumbnails' => json_encode([
//                    [
//                        'height' => 344,
//                        'width' => 234,
//                        'type' => 'pc',
//                        'urls' => ['https://example.com/mia-thumbs.jpg']
//                    ]
//                ]),
//            ],
//        ]);
    }
}
