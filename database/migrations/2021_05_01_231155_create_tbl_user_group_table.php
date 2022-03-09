<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('_guid', 22)->nullable();
            $table->string('_name')->nullable();
            $table->string('_ownerGuid', 22)->nullable();
            $table->string('_ownerName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_group');
    }
}
