<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_data', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('main_title_id')->nullable();
            $table->foreign('main_title_id')->references('id')->on('section_data_mains')->onDelete('cascade');
            $table->tinyInteger('view_type')->nullable()->comment('0: story_view,1: video,2:categories');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('video_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->text('blog_course_article_id')->nullable();
            $table->text('blog_article_id')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('section_data');
    }
}
