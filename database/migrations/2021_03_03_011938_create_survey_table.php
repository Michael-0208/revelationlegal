<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey', function (Blueprint $table) {
            $table->id('survey_id');
            $table->unsignedBigInteger('account_id');
            $table->string('survey_name');
            $table->tinyInteger('survey_active')->default(0);
            $table->dateTime('survey_created_dt')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('cust_1_label');
            $table->string('cust_2_label');
            $table->string('cust_3_label');
            $table->string('cust_4_label');
            $table->string('cust_5_label');
            $table->string('cust_6_label');

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
        Schema::dropIfExists('survey');

    }
}
