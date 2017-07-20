<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('status');
            $table->timestamps();
            
            $table->text('requirement')->nullable();
            $table->string('wrike_project_id_v2')->nullable();
            $table->string('wrike_project_id_v3')->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('rph', 10, 2)->nullable();
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
