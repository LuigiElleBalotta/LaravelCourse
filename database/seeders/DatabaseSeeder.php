<?php

namespace Database\Seeders;

use App\Models\{AlbumCategory, Category, User, Photo, Album};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(30)->create();

        // $this->call(UserSeeder::class);
        // $this->call(AlbumSeeder::class);
        // $this->call(PhotoSeeder::class);

        // Per non eseguire le migrazioni e eseguire direttamente db:seed (Altrimenti usare subito le factory)
        // Disabilitiamo le foreign key per poterle poi eliminare
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Tronchiamo le tabelle
        User::truncate();
        AlbumCategory::truncate();
        Category::truncate();
        Album::truncate();
        Photo::truncate();

        // Eseguiamo le insert
        // Crea per ogni utente 100 album e 100 foto per ogni album
        $this->call(CategorySeeder::class);
        User::factory(200)->has(
            Album::factory(100)->has(
                Photo::factory(100)
            )
        )->create();

        $this->call(AlbumCategory::class);


    }
}
