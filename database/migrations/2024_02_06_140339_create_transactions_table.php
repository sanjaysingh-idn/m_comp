<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('invoice_id')->unique();
            $table->integer('total_items');
            $table->integer('total_price');
            $table->integer('discount')->nullable();
            $table->integer('final_price');
            $table->integer('cash');
            $table->integer('change');
            $table->integer('ongkir')->nullable();
            $table->string('nama_penerima');
            $table->string('alamat');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('nomor_hp');
            $table->enum('status', ['diproses', 'dikirim', 'selesai'])->default('diproses');
            $table->string('no_resi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
