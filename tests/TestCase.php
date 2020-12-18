<?php namespace Tests;

use Monolog\Handler\StreamHandler;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Symfony\Component\Process\Process;

class TestCase extends BaseTestCase
{
    protected function defineEnvironment($app) {
        $app['config']->set('logging', [
            'default' => 'console',
            'channels' => [
                'console' => [
                    'driver' => 'stack',
                    'channels' => ['stderr', 'stdout'],
                    'ignore_exceptions' => false,
                ],
                'stdout' => [
                    'driver' => 'monolog',
                    'formatter' => env('LOG_STDERR_FORMATTER'),
                    'handler' => StreamHandler::class,
                    'with' => [
                        'stream' => 'php://stdout',
                    ],
                    'level' => 'debug',
                ],

                'stderr' => [
                    'driver' => 'monolog',
                    'formatter' => env('LOG_STDERR_FORMATTER'),
                    'handler' => StreamHandler::class,
                    'with' => [
                        'stream' => 'php://stderr',
                        'bubble' => false,
                    ],
                    'level' => 'warning',
                ],
            ]
        ]);
    }

    protected function mock_command_line($output, $command = null, $path = null) {
        $mockProcess = \Mockery::mock('overload:' . Process::class);

        if(empty($command)) {
            $mockProcess->shouldReceive('fromShellCommandline')->andReturn($mockProcess);
        } else {
            $mockProcess->shouldReceive('fromShellCommandline')
                        ->with($command, $path)
                        ->andReturn($mockProcess);
        }

        $mockProcess->shouldReceive('mustRun')->andReturn();
        $mockProcess->shouldReceive('isSuccessful')->andReturn(true);
        $mockProcess->shouldReceive('getOutput')->andReturn($output);
    }
}