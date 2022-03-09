<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('goal_id')->index('goal_id');
            $table->unsignedInteger('goal_type')->index('goal_type');
            $table->float('value', 10, 0)->default(0);
            $table->date('date')->index('date');
            $table->boolean('is_scale');
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
        Schema::dropIfExists('tbl_logs');
    }
}
