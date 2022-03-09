<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_goals', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('type_id')->index('type_id');
            $table->boolean('is_active')->nullable()->default(1)->index('is_active');
            $table->boolean('is_show_lobby')->nullable()->default(1)->index('is_show_lobby');
            $table->string('name');
            $table->string('user_id')->index('user_id');
            $table->date('due_date')->nullable();
            $table->date('habit_start_date')->nullable();
            $table->longText('status')->nullable();
            $table->longText('improvement')->nullable();
            $table->longText('risk')->nullable();
            $table->longText('benefits')->nullable();
            $table->longText('vision')->nullable();
            $table->longText('vision_decades')->nullable();
            $table->longText('barriers')->nullable();
            $table->longText('priority')->nullable();
            $table->longText('initiative')->nullable();
            $table->longText('help')->nullable();
            $table->longText('support')->nullable();
            $table->longText('environment')->nullable();
            $table->longText('imagery')->nullable();
            $table->integer('task_template_id')->nullable()->default(0);
            $table->integer('level')->nullable()->default(0);
            $table->integer('parent_id')->nullable()->default(0)->index('parent_id');
            $table->integer('top_parent_id')->nullable()->default(0)->index('top_parent_id');
            $table->boolean('has_sub')->nullable()->default(0);
            $table->decimal('percent', 5)->nullable()->default(0.00);
            $table->boolean('is_end')->nullable()->default(0)->index('is_end');
            $table->boolean('is_delete')->nullable()->default(0)->index('is_delete');
            $table->integer('self_order')->nullable()->index('self_order');
            $table->integer('list_order')->nullable()->index('list_order');
            $table->integer('detail_order')->comment('Order for detail page');
            $table->tinyInteger('self_collapse')->nullable();
            $table->tinyInteger('list_collapse')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->boolean('is_in_trophy')->default(0);
            $table->boolean('is_default')->default(0)->index('is_default');
            $table->string('auto_save_id')->unique('auto_save_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_goals');
    }
}
