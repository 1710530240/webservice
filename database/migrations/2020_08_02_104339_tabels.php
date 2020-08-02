<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wisata', function(Blueprint $table){
            $table->string('id', 5)->primary();
            $table->string('nama', 150);
            $table->mediumText('lokasi')->nullable();
            $table->enum('kategori', array('Hutan', 'Gunung', 'Lainnya', 'Pantai', 'Air terjun', 'Bukit'))->default('Lainnya');
            $table->string('koordinat', 150)->nullable();
        });

        Schema::create('users', function(Blueprint $tabel){
            $tabel->string('id', 5)->primary();
            $tabel->string('username', 150)->unique();
            $tabel->string('email', 150)->unique();
            $tabel->string('password', 150);
            $tabel->string('nohp', 15)->unique();
            $tabel->text('alamat');
        });

        Schema::create('ulasan', function(Blueprint $tabel){
            $tabel->string('id', 5)->primary();
            $tabel->string('wisata', 5);
            $tabel->string('user', 5);
            $tabel->mediumText('ulasan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
