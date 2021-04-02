<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('farmers')->nullable();
            $table->integer('clients')->nullable();
            $table->integer('staffs')->nullable();
            $table->integer('awards')->nullable();
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
        Schema::dropIfExists('counter_data');
    }
}
