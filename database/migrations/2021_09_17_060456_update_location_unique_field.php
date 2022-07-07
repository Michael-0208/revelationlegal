<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLocationUniqueField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblLocation', function (Blueprint $table) {
            $table->unique(['survey_id', 'location'], 'unique_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblLocation', function (Blueprint $table) {
            $table->dropUnique('unique_location');
        });
    }
}
