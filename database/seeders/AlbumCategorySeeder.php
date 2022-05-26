<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\AlbumCategory;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = Category::all()->pluck('id');
        $albums = Album::all();

        foreach ($albums as $album) {
            $categories = $cats->shuffle()->toArray();
            AlbumCategory::create([
                'album_id' => $album->id,
                'category_id' => $categories[0],
            ]);

            AlbumCategory::create([
                'album_id' => $album->id,
                'category_id' => $categories[1],
            ]);

            AlbumCategory::create([
                'album_id' => $album->id,
                'category_id' => $categories[2],
            ]);
        }
    }
}
