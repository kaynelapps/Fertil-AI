<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('display_name')->nullable();
            $table->string('timezone')->nullable()->default('UTC');
            $table->timestamp('last_notification_seen')->nullable();
            $table->string('player_id')->nullable();
            $table->tinyInteger('goal_type')->nullable()->comment('0: track cycle,1: track pragnancy');
            $table->dateTime('period_start_date')->nullable();
            $table->timestamp('conversion_date')->nullable();
            $table->integer('cycle_length')->comment('In Days');
            $table->integer('period_length')->comment('In Days');
            $table->integer('luteal_phase')->comment('In Days');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_type')->nullable();
            $table->integer('age')->nullable();
            $table->tinyInteger('is_linked')->default(0)->nullable();
            $table->string('pairing_code')->nullable();
            $table->string('partner_name')->nullable();
            $table->string('region')->nullable();
            $table->string('country_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('city')->nullable();
            $table->rememberToken();
            $table->string('status')->default('active');
            $table->enum('is_backup',['on','off'])->default('off');
            $table->longText('encrypted_user_data')->nullable();
            $table->timestamp('last_sync_date')->nullable();
            $table->string('app_version')->nullable();
            $table->text('app_source')->nullable();
            $table->dateTime('last_actived_at')->nullable();
            $table->string('otp')->nullable();
            $table->string('revenuecat_app_id')->nullable();
            $table->tinyInteger('is_subscription')->default(0);
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
        Schema::dropIfExists('users');
    }
}
