<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Hash;
use \App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(30)->create();
        /*
        for( $i = 0; $i < 30; $i++ ) {

            $name = Str::random(10);

            // DB::insert('insert into users ( name, email, password, created_at, email_verified_at ) values( ?, ?, ?, ?, ? );', [
            //     $name,
            //     "$name@test.nonesiste.com",
            //     Hash::make('Cambiala09!'),
            //     Carbon::now(),
            //     Carbon::now()
            // ]);
            // OPPURE: 
            
            DB::table('users')->insert([
                'name' => $name,
                'email' => "$name@test.nonesiste.com",
                'password' => Hash::make('Cambiala09!'),
                'created_at' => Carbon::now(),
                'email_verified_at' => Carbon::now()
            ]);
        }
        */
        
    }
}
