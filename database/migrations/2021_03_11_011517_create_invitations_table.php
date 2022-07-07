<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->string('sender');
            $table->timestamp('complete_by_date')->nullable();
            $table->string('managing_partner_name');
            $table->string('firm_domain_name')->nullable();
            $table->string('questions_contact_name')->nullable();
            $table->string('instructions_pdf_link')->nullable();
            $table->string('instructions_pdf_link_2')->nullable();
            $table->text('invitation_letter_template')->nullable();
            $table->string('email_subject')->nullable();
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
        Schema::dropIfExists('invitations');
    }
}
