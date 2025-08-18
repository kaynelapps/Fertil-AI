<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePregnancyDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pregnancy_dates', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('pregnancy_date')->nullable();
            $table->unsignedBigInteger('article_id')->nullable();
            $table->tinyInteger('status')->nullable()->default('0');
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
        Schema::dropIfExists('pregnancy_dates');
    }
}
