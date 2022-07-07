<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespondentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respondents', function (Blueprint $table) {
            $table->id('resp_id');
            $table->unsignedBigInteger('survey_id');
            $table->string('resp_access_code', 20);
            $table->string('resp_email', 80)->nullable();
            $table->string('resp_first', 80);
            $table->string('resp_last', 80);
            $table->tinyInteger('resp_alt')->default(0);
            $table->string('cust_1', 255)->nullable();
            $table->string('cust_2', 255)->nullable();
            $table->string('cust_3', 255)->nullable();
            $table->string('cust_4', 255)->nullable();
            $table->string('cust_5', 255)->nullable();
            $table->string('cust_6', 255)->nullable();
            $table->string('cust_7', 255)->nullable();
            $table->string('cust_8', 255)->nullable();
            $table->string('cust_9', 255)->nullable();
            $table->string('cust_10', 255)->nullable();
            $table->string('cust_11', 255)->nullable();
            $table->string('cust_12', 255)->nullable();
            $table->string('cust_13', 255)->nullable();
            $table->string('cust_14', 255)->nullable();
            $table->string('cust_15', 255)->nullable();
            $table->string('cust_16', 255)->nullable();
            $table->string('cust_17', 255)->nullable();
            $table->integer('rentable_square_feet')->nullable();
            $table->float('resp_compensation', 10, 2)->default(0.00);
            $table->float('resp_bonus', 10, 2)->nullable()->default(0.00);
            $table->float('resp_benefit_pct', 5, 4)->default(0.0000);
            $table->float('resp_total_compensation', 10, 2)->default(0.00);
            $table->datetime('start_dt')->nullable();
            $table->datetime('last_dt')->nullable();
            $table->integer('last_page_id')->nullable();
            $table->tinyInteger('survey_completed')->default(0);
            $table->tinyInteger('invitation_sent')->unsigned()->default(0)->comment('0 - not sent.  1-> how many sent');
            $table->integer('survey_reviewed')->default(0);
            $table->datetime('last_invitation_sent')->nullable();
            $table->string('reportLocation1', 200)->nullable();
            $table->string('reportLocation2', 200)->nullable();
            $table->timestamps();
            $table->foreign('survey_id')->references('survey_id')->on('tblSurvey');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respondents');
    }
}
