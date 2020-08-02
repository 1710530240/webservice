<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users', function(Blueprint $tabel){
            $tabel->string('username', 150)->primary();
            $tabel->string('email', 150)->unique();
            $tabel->string('nama_lengkap', 150)->nullable();
            $tabel->string('password', 150);
            $tabel->enum('kelamin', array('L', 'P'));
            $tabel->string('nohp', 25)->unique()->nullable();
            $tabel->text('alamat');
        });

        Schema::create('wisata', function(Blueprint $table){
            $table->string('id', 5)->primary();
            $table->string('nama', 150);
            $table->mediumText('lokasi')->nullable();
            $table->enum('kategori', array('Hutan', 'Gunung', 'Lainnya', 'Pantai', 'Air terjun', 'Bukit'))->default('Lainnya');
            $table->string('koordinat', 150)->nullable();
            $table->string('penulis', 5);
            $table->foreign('penulis')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('ulasan', function(Blueprint $tabel){
            $tabel->string('id', 5)->primary();
            $tabel->string('wisata', 5);
            $tabel->string('user', 5);
            $tabel->mediumText('ulasan');
            $tabel->foreign('wisata')->references('id')->on('wisata')->onDelete('cascade')->onUpdate('cascade');
            $tabel->foreign('user')->references('username')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ulasan');
        Schema::dropIfExists('wisata');
        Schema::dropIfExists('users');
    }
}
