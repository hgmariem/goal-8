<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('guid')->nullable();
            $table->integer('plan_id')->nullable();
            $table->text('transaction_id')->nullable();
            $table->date('subscription_from_date')->nullable();
            $table->date('subscription_till_date')->nullable();
            $table->text('meta_data')->nullable();
            $table->string('payment_status', 191)->nullable()->default('PENDING');
            $table->date('created_at');
            $table->date('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscription');
    }
}
