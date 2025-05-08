<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Scope;
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
        // TODO: ordena, timespamps beti bukaeran
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->longText('text');
            $table->string('active')->default(false);
            // TODO: ezin da nulable izan, beti norbaitek sortzen dut task bat Ikusi 14319 ataza
            $table->unsignedBigInteger('user_id')->nullable(true);
            // TODO: ezin da nulable izan, beti zutabe batean egon beharko du Ikusi 14319 ataza
            $table->unsignedBigInteger('column_id')->nullable(true);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('column_id')->references('id')->on('columns');

            //TODO: order zutabea falta da Ikusi 14319 ataza
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
