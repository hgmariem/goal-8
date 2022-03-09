<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblGoalStatementValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_goal_statement_values', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('goal_id')->nullable()->default(0);
            $table->string('user_id')->nullable()->index('user_id');
            $table->string('meta_key');
            $table->text('meta_attr')->nullable();
            $table->longText('meta_value')->nullable();
            $table->string('meta_type', 225);
            $table->boolean('is_active')->default(1);
            $table->boolean('show_in_lobby')->nullable()->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_goal_statement_values');
    }
}
