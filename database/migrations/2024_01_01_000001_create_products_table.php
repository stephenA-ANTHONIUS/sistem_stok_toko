<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('id_produk')->unique();
            $table->integer('jumlah_stok')->default(0);
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('harga_offline', 15, 2)->default(0);
            $table->decimal('harga_online', 15, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
