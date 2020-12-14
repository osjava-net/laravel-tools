<?php namespace Tests\Supports;

use HelpersTest;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testShell() {
        $commands = ['pwd', 'ls -l'];

        $output = shell($commands);

        foreach ($output as $key => $command) {
            echo "Running command [$key] and return: \n". $command . "\n\n";
        }
    }
}
