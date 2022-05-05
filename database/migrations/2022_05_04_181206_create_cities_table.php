<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{

    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->string('country');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
