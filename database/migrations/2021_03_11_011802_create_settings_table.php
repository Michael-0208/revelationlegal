<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->integer('show_splash_page');
            $table->text('splash_page')->nullable();
            $table->text('begin_page')->nullable();
            $table->text('end_page')->nullable();
            $table->integer('show_summary');
            $table->text('footer')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('weekly_hours_text')->nullable();
            $table->text('annual_legal_hours_text')->nullable();
            $table->string('logo_splash')->nullable();
            $table->string('logo_survey')->nullable();
            $table->string('cobrand_logo')->nullable();
            $table->integer('show_progress_bar')->default(1);
            $table->string('copyright')->nullable();
            $table->string('legal_yn_text')->nullable();
            $table->string('location_dist_text')->nullable();
            $table->integer('show_location_dist')->default(0);
            $table->integer('show_legal_services')->default(1);
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
        Schema::dropIfExists('settings');
    }
}
