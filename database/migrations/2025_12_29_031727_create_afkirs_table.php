<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfkirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afkirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained('asets')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            $table->integer('jumlah')->nullable();
            $table->date('tgl_afkir')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('tindak_lanjut')->nullable();
            $table->text('alasan')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('afkirs');
    }
}
