<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForecastsTable extends Migration
{

    public function up()
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->dateTime('start_time');
            $table->decimal('temp');
            $table->decimal('feels_like');
            $table->decimal('pressure');
            $table->decimal('humidity');
            $table->decimal('dew_point');
            $table->decimal('uvi');
            $table->decimal('clouds');
            $table->decimal('visibility');
            $table->decimal('wind_speed');
            $table->decimal('wind_deg');
            $table->decimal('wind_gust');
            $table->json('description');
            $table->decimal('pop');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forecasts');
    }
}
