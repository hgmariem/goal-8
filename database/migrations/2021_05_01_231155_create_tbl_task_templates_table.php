<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTaskTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_task_templates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('task_id');
            $table->string('task_name')->nullable();
            $table->string('template_name')->nullable();
            $table->integer('repeat_qty');
            $table->string('repeat_frequency', 100);
            $table->string('repeat_on', 100);
            $table->integer('repeat_on_date')->nullable()->default(0);
            $table->string('add_suffix', 15);
            $table->string('ends_on', 50);
            $table->string('end_on_value');
            $table->string('begin_on', 50)->default('');
            $table->string('begin_on_value')->default('');
            $table->boolean('status')->default(1);
            $table->boolean('is_repeat_done')->default(0);
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
        Schema::dropIfExists('tbl_task_templates');
    }
}
