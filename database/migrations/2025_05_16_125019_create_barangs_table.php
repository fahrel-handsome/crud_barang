<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangsTable extends Migration
{
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id(); // kolom primary key id
            $table->string('kode')->unique(); // kode unik barang
            $table->string('nama_barang'); // nama barang
            $table->text('deskripsi')->nullable(); // deskripsi barang, boleh kosong
            $table->decimal('harga_satuan', 15, 2); // harga satuan dengan 2 angka desimal
            $table->integer('jumlah'); // jumlah stok barang
            $table->string('foto')->nullable(); // path foto, boleh kosong
            $table->timestamps(); // created_at dan updated_at otomatis
        });
    }

    public function down()
    {
        Schema::dropIfExists('barangs'); // jika rollback, hapus tabel barangs
    }
}
