<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_experts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tag_line');
            $table->text('short_description')->nullable();
            $table->text('career')->nullable();
            $table->text('education')->nullable();
            $table->text('awards_achievements')->nullable();
            $table->text('area_expertise')->nullable();
            $table->unsignedBigInteger('is_access')->nullable()->default('0')->comment('0-disable, 1-enable');
            $table->integer('experience')->default(0);
            $table->string('gender')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('health_experts');
    }
}
