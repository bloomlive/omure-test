<?php

namespace Database\Seeders;

use App\Jobs\UpdateCitiesForecastsJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->callOnce(CitiesSeeder::class);

        UpdateCitiesForecastsJob::dispatchSync();
    }
}
