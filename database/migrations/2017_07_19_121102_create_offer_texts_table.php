<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferTextsTable extends Migration
{
    public function up()
    {
        Schema::create('offer_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text')->nullable();
            $table->string('type'); // entweder "standard" oder "custom"
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('offer_texts');
    }
}
