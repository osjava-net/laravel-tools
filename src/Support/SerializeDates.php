<?php


namespace QFrame\Support;


use DateTimeInterface;

trait SerializeDates
{
    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }
}
