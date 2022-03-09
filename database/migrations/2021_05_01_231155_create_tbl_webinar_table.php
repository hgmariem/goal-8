<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblWebinarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_webinar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->dateTime('date')->nullable();
            $table->string('url')->default('');
            $table->string('register_url');
            $table->string('groups_allowed', 2000)->nullable();
            $table->boolean('is_deleted')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_webinar');
    }
}
