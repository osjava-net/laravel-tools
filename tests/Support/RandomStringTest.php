<?php namespace Tests\Support;

use Illuminate\Support\Facades\Log;
use QFrame\Support\RandomString;
use Tests\TestCase;

class RandomStringTest extends TestCase
{
    use RandomString;

    function testRandomAlphaNumber() {
        $str = $this->random_alpha_number(16);
        Log::debug($str);
    }
}
