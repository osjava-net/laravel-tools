<?php namespace QFrame\Support\Facades;

/**
 * Class Version
 * @package QFrame\Support\Facades
 *
 * @method static string get()
 */
class Version extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor() {
        return 'version';
    }
}