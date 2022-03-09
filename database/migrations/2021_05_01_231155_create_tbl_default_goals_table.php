<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDefaultGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_default_goals', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('type_id');
            $table->boolean('is_active')->nullable()->default(1);
            $table->boolean('is_show_lobby')->nullable()->default(1);
            $table->string('name');
            $table->string('user_id');
            $table->date('due_date')->nullable();
            $table->date('habit_start_date')->nullable();
            $table->text('status')->nullable();
            $table->text('improvement')->nullable();
            $table->text('risk')->nullable();
            $table->text('benefits')->nullable();
            $table->text('vision')->nullable();
            $table->text('vision_decades')->nullable();
            $table->text('barriers')->nullable();
            $table->text('priority')->nullable();
            $table->text('initiative')->nullable();
            $table->text('help')->nullable();
            $table->text('support')->nullable();
            $table->text('environment')->nullable();
            $table->text('imagery')->nullable();
            $table->integer('level')->nullable()->default(0);
            $table->integer('parent_id')->nullable();
            $table->integer('top_parent_id')->nullable();
            $table->boolean('has_sub')->nullable()->default(0);
            $table->decimal('percent', 5)->nullable()->default(0.00);
            $table->boolean('is_end')->nullable()->default(0);
            $table->boolean('is_delete')->nullable()->default(0);
            $table->integer('self_order')->nullable();
            $table->integer('list_order')->nullable();
            $table->integer('detail_order')->comment('Order for detail page');
            $table->tinyInteger('self_collapse')->nullable();
            $table->tinyInteger('list_collapse')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->boolean('is_in_trophy')->nullable()->default(0);
            $table->boolean('is_default')->nullable()->default(1);
            $table->string('auto_save_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_default_goals');
    }
}
