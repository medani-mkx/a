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
            $table->string('wrike_task_id_v3')->nullable();
            $table->string('wrike_title')->nullable();
            $table->string('title');
            $table->string('wrike_description')->nullable();
            $table->string('description')->nullable();
            $table->string('wrike_effort', 10, 2)->nullable();
            $table->decimal('effort', 10, 2)->nullable();
            $table->decimal('wrike_effort_design', 10, 2)->nullable();
            $table->decimal('effort_design', 10, 2)->nullable();
            $table->decimal('wrike_effort_tech', 10, 2)->nullable();
            $table->decimal('effort_tech', 10, 2)->nullable();
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
