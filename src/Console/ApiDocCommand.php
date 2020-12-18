<?php

namespace QFrame\Console;

use QFrame\Support\ApiDocEntity;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class ApiDocCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apidoc {--v|view : Display the details for generation docs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the documents of APIs.';

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

        $this->sourceFolder = config('apidoc.source_folder');
        $this->outputFolder = config('apidoc.output_folder');
        $this->excludes = config('apidoc.excludes');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $foundCommand = shell_exec('which asciidoctor');
        if (empty($foundCommand) || Str::contains($foundCommand, 'not found')) {
            Log::error("Not found Asciidoctor and install it first.");
            return 1;
        }

        if(!File::deleteDirectory(config('apidoc.doc_folder') . '/_src')) {
            Log::debug("Delete the docs directory is failure.");
        }

        foreach (Arr::wrap($this->sourceFolder) as $source) {
            /** @var Finder $files */
            $files = path_finder($source, $this->excludes, sprintf('*%s', config('apidoc.class_suffix')));

            foreach ($files as $file) {
                if ($this->hasOption('view')) {
                    Log::info("Generate doc form: $file");
                }

                $docEntity = new ApiDocEntity($file->getPath(), $file->getBasename(config('apidoc.class_suffix')));

                $docEntity->saveTo(config('apidoc.doc_folder') . '/_src', config('apidoc.doc_file_ext'));
            }
        }

        $shell = sprintf('cd %s && asciidoctor -b html5 -D %s *.adoc', config('apidoc.doc_folder'),
            config('apidoc.html_folder'));
        shell_exec($shell);

        return 0;
    }

    private function extractApiDoc($content) {
        if (preg_match('/<apidoc>([^<\/]*)<\/apidoc>/i', $content, $matches)) {
            return preg_replace('/^\/\*{1,2}|\s*\*\/$|^\s*\*\040?/m', '', $matches[1]);
        } else {
            return null;
        }
    }
}
