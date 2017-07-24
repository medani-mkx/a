<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offer_id');
            $table->boolean('wrike_has_parent_tasks');
            $table->boolean('wrike_has_child_tasks');
            $table->string('wrike_task_id_v3')->unique()->nullable();
            $table->string('wrike_title')->nullable();
            $table->string('title');
            $table->decimal('price', 10, 2)->nullable();
            $table->text('wrike_description')->nullable();
            $table->text('description')->nullable();
            $table->time('wrike_effort')->nullable();
            $table->time('effort')->nullable();
            $table->time('wrike_effort_design')->nullable();
            $table->time('effort_design')->nullable();
            $table->time('wrike_effort_tech')->nullable();
            $table->time('effort_tech')->nullable();
            $table->boolean('wrike_optional')->nullable();
            $table->boolean('optional')->nullable();
            $table->boolean('visible')->nullable();
            $table->boolean('included_in_the_price')->nullable();
            $table->string('special_rph')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
