<?php

use App\Models\DefaultLogCategory;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultLogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_log_categories', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('blog_link')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });

        $data = [
            [
                'id' => 1,
                'type' => 'meditation',
                'name' => 'Meditation',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 2,
                'type' => 'sleep',
                'name' => 'Sleep',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 3,
                'type' => 'water',
                'name' => 'Water',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 4,
                'type' => 'notes',
                'name' => 'Notes',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 5,
                'type' => 'weight',
                'name' => 'Weight',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 6,
                'type' => 'temparature',
                'name' => 'Temparature',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
        ];

        foreach ($data as $value) {
            if (!DefaultLogCategory::where('type', $value['type'])->exists()) {
                DefaultLogCategory::create($value);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_log_categories');
    }
}
