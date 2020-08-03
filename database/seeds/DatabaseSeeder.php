<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $wisata = [];
        $users = [];
        $ulasan = [];
        $kelamin = ['L', 'P'];
        $faker = Faker::create('id_ID');
        $kategori = array('Hutan', 'Gunung', 'Lainnya', 'Pantai', 'Air terjun', 'Bukit');

        for ($i=1; $i <= 5 ; $i++) {
            $users[] = [
                'username' => 'USR-' . $i,
                'email' => 'usr' . $i . '@gmail.com',
                'password' => Hash::make( 'passusr' . $i),
                'kelamin' => $kelamin[rand(0,1)],
                'alamat' => $faker->address(50),
                'nohp' => $faker->phoneNumber(12),
                'nama_lengkap' => $faker->name()
            ];
        }

        for ($i=1; $i <= 15; $i++) {
           $wisata[] = [
               'id' => Str::random(5),
               'nama' => 'pariwisata ' . $i,
               'lokasi' => $faker->address(100),
               'kategori' => $kategori[rand(0, 5)],
               'penulis' => $users[rand(0, 4)]['username'],
           ];
        }



        for ($i=1 ; $i <= 25 ; $i++ ) {
            $ulasan[] = [
                'id' => Str::random(5),
                'wisata' => $wisata[rand(0, 14)]['id'],
                'user' => $users[rand(0, 4)]['username'],
                'ulasan' => $faker->text(50)
            ];
        }
        DB::table('users')->insert($users);
        DB::table('wisata')->insert($wisata);
        DB::table('ulasan')->insert($ulasan);
    }
}
