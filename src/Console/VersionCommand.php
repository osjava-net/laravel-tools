<?php

namespace QFrame\Console;

use QFrame\Support\ApiDocEntity;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use QFrame\Support\Facades\Version;
use Symfony\Component\Finder\Finder;

class VersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display or manage the version of the application';

    /**
     * @var array
     */
    protected $sourceFolder;

    /**
     * @var string
     */
    protected $outputFolder;

    /**
     * @var null|string|array
     */
    protected $excludes;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->getOutput()->info(Version::get());
        return 0;
    }
}
