<?php namespace QFrame\Support\Template;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class TimestampFilter implements VariableFilter
{
    const NAME = 'timestamp';

    /**
     * <code>some_value|timestamp</code>
     * @param int $value
     * @param mixed ...$args
     * @return string
     */
    function filter($value, ...$args) {
        return Carbon::createFromTimestampMs($value)->format(Arr::first($args));
    }
}
