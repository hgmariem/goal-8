<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblHabitTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_habit_types', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('goal_id');
            $table->unsignedTinyInteger('is_scale');
            $table->float('minimum', 10, 0)->unsigned()->default(0);
            $table->float('maximum', 10, 0)->unsigned()->default(0);
            $table->unsignedTinyInteger('is_apply')->default(0);
            $table->unsignedInteger('type');
            $table->string('value');
            $table->string('count_per_week');
            $table->text('text')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_habit_types');
    }
}
