<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCycleDateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cycle_date_data', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('slide_type')->comment('0: text-message , 1 : question-answer')->nullable();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->unsignedBigInteger('cycle_date_id')->nullable();
            $table->foreign('cycle_date_id')->references('id')->on('cycle_dates')->onDelete('cascade');
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('cycle_date_data');
    }
}
