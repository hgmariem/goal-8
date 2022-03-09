<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_id')->nullable();
            $table->string('module_type', 191)->nullable();
            $table->text('notify_text')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('push_notifications');
    }
}
