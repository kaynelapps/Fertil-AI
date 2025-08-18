<?php

use App\Models\CalculatorTool;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculatorToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculator_tools', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('blog_link')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });

         $data = [
            [
                'id' => 1,
                'type' => 'ovulation_calculator',
                'name' => 'Ovulation calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 2,
                'type' => 'pregnancy_test_calculator',
                'name' => 'Pregnancy test calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 3,
                'type' => 'period_calculator',
                'name' => 'Period calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 4,
                'type' => 'implantation_calculator',
                'name' => 'Implantation calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 5,
                'type' => 'pregnancy_due_date_calculator',
                'name' => 'Pregnancy due date calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
        ];

        foreach ($data as $value) {
            if (!CalculatorTool::where('type', $value['type'])->exists()) {
                CalculatorTool::create($value);
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
        Schema::dropIfExists('calculator_tools');
    }
}
