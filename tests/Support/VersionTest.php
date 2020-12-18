<?php namespace Tests\Support;

use QFrame\Services\Version;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class VersionTest extends TestCase
{
    protected function setUp(): void {
        parent::setUp();

    }

    public function testFullVersion() {
        $this->mock_command_line('1.0.1-beta.2020.01-2-gd000854-dirty');

        $version = new Version();

        echo 'Current Version: '. $version->get();

        self::assertEquals('1.0.1-beta.2020.01+d000854-dirty', $version->get());
    }
}