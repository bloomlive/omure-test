<?php

namespace App\Listeners;

use App\Events\ForecastUpdatedEvent;
use Illuminate\Support\Facades\Cache;

class ClearForecastCacheListener
{

    public function handle(ForecastUpdatedEvent $event)
    {
        $event->date->toDateTimeString();

        for ($i = 0; $i < 48; $i++) {
            $cityKey        = "forecasts.{$event->date->addHours($i)->startOfHour()->toDateTimeString()}.withCity";
            $withoutCityKey = "forecasts.{$event->date->addHours($i)->startOfHour()->toDateTimeString()}.withoutCity";

            Cache::forget($cityKey);
            Cache::forget($withoutCityKey);
        }
    }
}
