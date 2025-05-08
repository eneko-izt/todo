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
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('text');
            $table->string('active')->default(false);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('column_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('column_id')->references('id')->on('columns');

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
