<?php namespace QFrame\Support\Template;

interface VariableFilter
{
    function filter($value, ...$args);
}
