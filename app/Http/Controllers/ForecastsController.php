<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenWeatherMap\NoResultsException;
use App\Http\Requests\ForecastRequest;
use App\Http\Resources\ForecastResource;
use App\Jobs\UpdateCitiesForecastsJob;
use App\Models\Forecast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class ForecastsController extends Controller
{

    public function __invoke(ForecastRequest $request)
    {
        $validated = $request->validated();

        /**
         * We do not have to validate the validity of the request, because it is taken care of
         * in the FormRequest that is dependency injected. We only need to manipulate the
         * data in order to accommodate the database information structure.
         *
         */
        $date = Carbon::parse($validated['date'])->startOfHour();

        /**
         * Because we simply cannot acquire data any further, we throw an exception that is handled.
         * We could get the data, if we would be using a different (non-free) endpoint, but this
         * isn't the real goal of the task and does not demonstrate anything but my wallet.
         */
        if ($date->diffInSeconds(now()->startOfHour()) > 47 * 60 * 60) {
            throw new NoResultsException('We cannot see that far because of the API limitation.');
        }

        if ($date->isBefore(Forecast::query()->first()?->startTime)) {
            throw new NoResultsException('We cannot look at historic data because of the API limitation');
        }

        /**
         * We simply build a query that we will use in one or two places, depending on the nature
         * of the return on COUNT(). It is somewhat inefficient to throw an error here, because
         * it makes an additional query that could be avoided by returning an empty response.
         */

        $query = Forecast::query()
            ->where('start_time', '=', $date)
            ->when($validated['city'] ?? false, function (Builder $builder) {
                $builder->with('city');
        });

        /**
         * Because of the missing query parameter on the request to the API, it will be painfully slow.
         * The workaround for this would be to request data every hour instead of the required 4
         * times a day, which would make sense, if we would be using a different endpoint.
         */
        if (!$query->count()) {
            UpdateCitiesForecastsJob::dispatchSync();
        }

        /**
         * Because I was required to use an event, we now return data from cache instead when possible.
         * Normally I would use an observer and watch data change instead, however this would not
         * be accepted, apparently, so I had to work around the way I usually would work.
         */

        $cityStatus = isset($validated['city']) ? 'withCity' : 'withoutCity';

        $cacheKey = "forecasts.{$date->toDateTimeString()}.{$cityStatus}";

        return Cache::rememberForever($cacheKey, function() use ($query) {
            return ForecastResource::collection($query->get());
        });
    }
}
