<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_type_id')->nullable();
            $table->foreign('application_type_id')->references('id')->on('application_types')->onDelete('set null');
            $table->unsignedBigInteger('applicant_user_id')->nullable();
            $table->foreign('applicant_user_id')->references('id')->on('users')->onDelete('set null');
            $table->text('uri')->nullable();
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
        Schema::dropIfExists('applications');
    }
}
