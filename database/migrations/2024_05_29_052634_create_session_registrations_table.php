<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educational_session_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('is_cancelled')->nullable()->comment('0:No , 1:Yes');
            $table->tinyInteger('status')->nullable()->comment('0:No , 1:Yes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_registrations');
    }
}
