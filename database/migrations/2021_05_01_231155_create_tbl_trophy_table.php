<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTrophyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_trophy', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('item_id');
            $table->string('name');
            $table->dateTime('trophy_date')->nullable();
            $table->boolean('deleted')->default(0);
            $table->string('user_id');
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
        Schema::dropIfExists('tbl_trophy');
    }
}
