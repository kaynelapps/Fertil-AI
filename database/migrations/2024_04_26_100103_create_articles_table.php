<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('tags_id')->nullable();
            $table->unsignedBigInteger('expert_id')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('goal_type');
            $table->tinyInteger('article_type')->default(0);
            $table->integer('weeks')->default(0);
            $table->tinyInteger('status')->nullable();
            $table->string('type')->default('free');
            $table->json('sub_symptoms_id')->nullable();
            $table->foreign('expert_id')->references('id')->on('health_experts')->onDelete('cascade');
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
        Schema::dropIfExists('articles');
    }
}
