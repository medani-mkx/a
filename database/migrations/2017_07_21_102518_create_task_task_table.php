<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parent_wrike_task_id_v3');
            $table->string('child_wrike_task_id_v3');
//            $table->foreign('parent_wrike_task_id_v3')->references('wrike_task_id_v3')->on('tasks')->onDelete('cascade');
//            $table->foreign('child_wrike_task_id_v3')->references('wrike_task_id_v3')->on('tasks')->onDelete('cascade');
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
        Schema::dropIfExists('task_tasks');
    }
}
