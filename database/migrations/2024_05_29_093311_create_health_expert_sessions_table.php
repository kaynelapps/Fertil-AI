<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthExpertSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_expert_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_expert_id')->nullable();
            $table->text('week_days')->nullable()->comment('1=monday,2=tuesday,3=wednesday,4=thursday,5=friday,6=saturday,7=sunday');
            $table->time('morning_start_time')->nullable();
            $table->time('morning_end_time')->nullable();
            $table->time('evening_start_time')->nullable();
            $table->time('evening_end_time')->nullable();
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
        Schema::dropIfExists('health_expert_sessions');
    }
}
