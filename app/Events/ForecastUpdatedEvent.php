<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Carbon;

class ForecastUpdatedEvent
{

    use Dispatchable;

    public Carbon $date;

    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }
}
