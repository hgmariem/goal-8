<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
          $table->string('sessionid', 225)->nullable();
          $table->string('guid')->nullable();
          $table->string('fullname');
          $table->string('gender')->nullable();
          $table->string('country')->nullable();
          $table->string('street')->nullable();
          $table->string('post_code')->nullable();
          $table->string('adress')->nullable();
          $table->string('lastloginms')->nullable();
          $table->string('logincount')->nullable();
          $table->string('telephone')->nullable();
          $table->text('groups')->nullable();
          $table->string('user_type')->nullable()->default('Students');
          $table->text('meta_data')->nullable();
          $table->boolean('activated')->nullable()->default(1);
          $table->text('user_token')->nullable();
          $table->string('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
