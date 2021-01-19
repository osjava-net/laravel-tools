<?php namespace QFrame\Support;
trait RandomString
{
    function random_alpha($length) {
        return $this->generate('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length);
    }

    function random_number($length) {
        return $this->generate('0123456789', $length);
    }

    function random_alpha_number($length) {
        return $this->generate('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length);
    }

    private function generate($source, $length) {
        $max = strlen($source);
        $dest = '';
        for ($index = 0; $index < $length; $index++) {
            $dest .= $source[mt_rand(0, $max)];
        }

        return $dest;
    }
}