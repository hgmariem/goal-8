<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblGoalMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_goal_meta', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('goal_id');
            $table->string('meta_key');
            $table->longText('meta_attr');
            $table->longText('meta_value')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_deleted')->default(0);
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
        Schema::dropIfExists('tbl_goal_meta');
    }
}
