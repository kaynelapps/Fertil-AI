<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insights', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('sub_symptoms_id')->nullable();
            $table->foreign('sub_symptoms_id')->references('id')->on('sub_symptoms')->onDelete('cascade');
            $table->tinyInteger('goal_type')->nullable()->comment('0: story_view,1: video,2:categories');
            $table->tinyInteger('insights_type')->default(0);
            $table->tinyInteger('view_type')->nullable()->comment('0: story_view,1: video,2:categories');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('video_id')->nullable();
            $table->foreign('video_id')->references('id')->on('videos_uploads')->onDelete('cascade');
            $table->tinyInteger('status')->nullable();
            $table->json('insights_data')->nullable();
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
        Schema::dropIfExists('insights');
    }
}
