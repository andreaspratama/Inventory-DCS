<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherLokasiToAsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->string('other_lokasi')->nullable()->after('ruang_id');
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
            $table->dropColumn('other_lokasi');
        });
    }
}
