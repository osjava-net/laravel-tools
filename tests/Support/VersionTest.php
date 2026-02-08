<?php namespace Tests\Support;

use QFrame\Support\Facades\Version;
use Tests\TestCase;

class VersionTest extends TestCase
{
    protected function setUp(): void {
        parent::setUp();

    }

    public function testFullVersion() {
        $this->mock_command_line('1.0.1-beta.2020.01-2-gd000854-dirty');

        $version = Version::get();//new Version();

        echo 'Current Version: '. $version;

        self::assertEquals('1.0.1-beta.2020.01+d000854-dirty', $version);
    }

    public function testOnlyVersion() {
        $this->mock_command_line('1.0.1-beta');

        $version = Version::get();//new Version();

        echo 'Current Version: '. $version;

        self::assertEquals('1.0.1-beta', $version);
    }
}