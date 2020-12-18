<?php namespace Tests\Support;

use QFrame\Providers\ToolsServiceProvider;
use QFrame\Support\Version;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class VersionTest extends TestCase
{
    protected function setUp(): void {
        parent::setUp();

    }

    protected function getPackageProviders($app) {
        return [ToolsServiceProvider::class,];
    }

    public function testFullVersion() {
        $this->mock_command_line('1.0.1-beta.2020.01-2-gd000854-dirty');

        $version = \QFrame\Support\Facades\Version::get();//new Version();

        echo 'Current Version: '. $version;

        self::assertEquals('1.0.1-beta.2020.01+d000854-dirty', $version);
    }
}