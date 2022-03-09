<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblWorkshopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_workshop', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id')->nullable();
            $table->string('title');
            $table->dateTime('date')->nullable();
            $table->string('day', 191)->nullable();
            $table->string('month', 191)->nullable();
            $table->string('year', 191)->nullable();
            $table->boolean('status')->default(1);
            $table->tinyInteger('is_older')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->dateTime('created_at')->default('0000-00-00 00:00:00');
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
        Schema::dropIfExists('tbl_workshop');
    }
}
