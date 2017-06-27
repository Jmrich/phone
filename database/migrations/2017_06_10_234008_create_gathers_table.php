<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGathersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gathers', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('gatherable');
            $table->string('input', 20)->default('dtmf');
            $table->unsignedInteger('timeout')->default(5);
            $table->string('finishOnKey')->default('#');
            $table->boolean('bargeIn')->default(1);
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
        Schema::dropIfExists('gathers');
    }
}
