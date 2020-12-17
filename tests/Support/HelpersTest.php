<?php namespace Tests\Support;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testShell() {
        $commands = ['pwd', 'git describe --always --tags --dirty'];

        $output = exec_command_line($commands);

        foreach ($output as $key => $command) {
            echo "Running command [$key] and return: \n". $command . "\n\n";
        }
    }

    public function testPathConcat() {
        $path = path_concat("http://localhost:8080/", "/api/v1", "method", " /index/");

        self::assertEquals('http://localhost:8080/api/v1/method/index', $path);
    }
}
