<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDefaultAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_default_assignments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('user_id')->index('user_id');
            $table->text('content');
            $table->integer('list_order')->nullable();
            $table->boolean('is_delete')->nullable()->default(0);
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
        Schema::dropIfExists('tbl_default_assignments');
    }
}
