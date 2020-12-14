<?php


namespace QFrame\Support;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class ValueObject implements \JsonSerializable
{
    // The attribute must be protected for serialization
    protected array $__data = array();

    public function __get($name) {
        if (Arr::has($this->__data, $name)) {
            return $this->__data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);

        return null;
    }

    public function __set($name, $value) {
        Log::debug("Set $name");
        $this->__data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->__data[$name]);
    }

    public function __unset($name)
    {
        unset($this->__data[$name]);
    }

    public function has($name) {
        return Arr::has($this->__data, $name) && $this->__data[$name] !== null;
    }

    public function jsonSerialize() {
        return $this->__data;
    }

    /**
     * @param array $attributes
     */
    public function fill($attributes) {
        $this->__data = array_merge($this->__data, $attributes);
    }
}
