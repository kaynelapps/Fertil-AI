<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionDataMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_data_mains', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->tinyInteger('goal_type')->nullable()->comment('0: track cycle,1: track pragnancy');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->tinyInteger('is_show_insights')->nullable()->comment('0: not Show,1: Show in Insights');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('dragondrop')->nullable();
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
        Schema::dropIfExists('section_data_mains');
    }
}
