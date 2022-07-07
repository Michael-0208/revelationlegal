<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id');
            $table->unsignedBigInteger('survey_id');
            $table->unsignedBigInteger('page_id');
            $table->string('question_desc')->nullable();
            $table->string('question_desc_alt')->nullable();
            $table->text('question_extra')->nullable();
            $table->text('question_extra_alt')->nullable();
            $table->string('question_code')->nullable();
            $table->string('question_UTBMS')->nullable();
            $table->tinyInteger('question_proximity_factor')->nullable();
            $table->tinyInteger('question_seq');
            $table->tinyInteger('question_enabled');
            $table->integer('question_id_original')->nullable();

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
        Schema::dropIfExists('questions');
    }
}
