<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCycleDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cycle_dates', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->tinyInteger('goal_type')->nullable();
            $table->tinyInteger('view_type')->nullable()->comment('0: story_view,1: video,2:categories');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->text('video_id')->nullable();
            $table->text('blog_course_article_id')->nullable();
            $table->text('blog_article_id')->nullable();
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
        Schema::dropIfExists('cycle_dates');
    }
}
