<?php namespace QFrame\Support\Template;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DatetimeFilter implements VariableFilter
{
    const NAME = 'date';

    /**
     * <code>some_value|date:format</code>
     * @param Carbon $value
     * @param mixed ...$args
     * @return string
     */
    function filter($value, ...$args) {
        Log::debug("Execute Datetime filter: value=$value", [$args]);
        return $value->format(Arr::first($args));
    }
}
