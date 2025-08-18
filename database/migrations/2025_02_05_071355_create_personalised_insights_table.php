<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalisedInsightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personalised_insights', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->json('users')->nullable();
            $table->json('anonymous_user')->nullable();
            $table->tinyInteger('goal_type')->nullable();
            $table->tinyInteger('insights_type')->default(0);
            $table->tinyInteger('view_type')->nullable()->comment('0: story_view,1: video,2:categories');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->text('video_id')->nullable();
            $table->text('blog_course_article_id')->nullable();
            $table->text('blog_article_id')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('view_all_users')->nullable()->default(0);
            $table->tinyInteger('view_all_anonymous_users')->nullable()->default(0);
            $table->json('personalinsights_data')->nullable();
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
        Schema::dropIfExists('personalised_insights');
    }
}
