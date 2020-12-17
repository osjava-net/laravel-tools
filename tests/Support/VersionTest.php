<?php namespace Tests\Support;

use QFrame\Services\Version;
use Tests\TestCase;

function exec_command_line($command) {
    return VersionTest::$functions->exec_command_line($command);
}

class VersionTest extends TestCase
{
    public static $functions;

    protected function setUp() : void {
        self::$functions = \Mockery::mock();
    }

    public function testFullVersion() {
        self::$functions->shouldReceive('exec_command_line')->andReturn('1.0.1-2-gd000854-dirty');
        $version = new Version();

        echo $version->full();
    }
}