<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_notify', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id');
            $table->text('reg_id');
            $table->string('device_id')->nullable();
            $table->text('token')->nullable();
            $table->enum('device_type', ['iOS', 'Android', 'Web', 'Tab'])->default('Web');
            $table->text('message');
            $table->dateTime('datetime');
            $table->enum('isRead', ['Read', 'UnRead'])->default('UnRead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_notify');
    }
}
