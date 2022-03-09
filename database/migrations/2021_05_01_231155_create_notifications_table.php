<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('from_id')->nullable();
            $table->integer('to_id')->nullable();
            $table->string('notification_event', 100)->nullable();
            $table->integer('action_id');
            $table->integer('role_id')->nullable();
            $table->string('text_notify');
            $table->text('extra_data')->nullable();
            $table->boolean('is_seen')->default(0)->comment('	0=>un_seen, 1=>seen');
            $table->boolean('status')->default(1)->comment('1');
            $table->text('push_response')->nullable();
            $table->dateTime('created_at')->default('0000-00-00 00:00:00');
            $table->dateTime('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
