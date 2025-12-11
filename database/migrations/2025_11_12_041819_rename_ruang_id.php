<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRuangId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->renameColumn('tempat', 'ruang_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->renameColumn('ruang_id', 'tempat');
        });
    }
}
