<?php namespace QFrame\Support;

use Illuminate\Support\Facades\Log;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ApiDocEntity
{
    /** @var string */
    protected $className;

    /** @var string */
    protected $package;

    /** @var string */
    protected $classComment;

    /** @var array */
    protected $methodComments = [];

    /**
     * DocEntity constructor.
     * @param $path
     * @param $className
     */
    public function __construct($path, $className) {
        $classPath = sprintf('%s/%s', config('apidoc.app_alias'), get_relative_path($path, app_path()));

        $this->package = preg_replace('/\//m', '\\', $classPath);
        $this->className = $className;

        $this->parse();
    }

    private function parse() {
        try {
            $refClass = new ReflectionClass($this->package . '\\' . $this->className);

            $this->classComment = $this->extractApiDoc($refClass->getDocComment());
            if ($this->classComment) {
                Log::debug("Parse the class comment of {$refClass->name}");
            }

            $refMethods = $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($refMethods as $method) {
                $content = $this->extractApiDoc($method->getDocComment());
                if ($content) {
                    $this->methodComments[$method->name] = $content;
                    Log::debug("Parse the method comment", [$method->name]);
                }
            }
        } catch (ReflectionException $e) {
            Log::debug("Ignore the exception as not found class", [$e->getMessage()]);
        }
    }

    private function extractApiDoc($content) {
        if (preg_match('/<apidoc>(.*?)<\/apidoc>/s', $content, $matches)) {
            $doc = preg_replace('/^\/\*{1,2}|\s*\*\/$|^\s*\*\040?/m', '', $matches[1]);
            $doc = preg_replace('/({@link ([^:}]+)})/m', '<<\\2>>', $doc);
            $doc = preg_replace('/({@link ([^::]+)::([^}]+)\(\)})/m', '<<\\2_\\3>>', $doc);
            return $doc;
        } else {
            return null;
        }
    }

    /**
     * @param string $rootPath
     * @param string $ext
     */
    public function saveTo($rootPath, $ext) {
        $outputPath = path_concat($rootPath, [preg_replace('/\\\\/m', '/', $this->package)]);
        $content = $this->generateTag($this->className, $this->classComment);

        foreach ($this->methodComments as $name => $comment) {
            $content .= $this->generateTag("{$this->className}_{$name}", $comment);
        }

        if (!empty($content)) {
            Log::info("Generate the document of {$this->package}\\{$this->className}");

            if (!is_dir($outputPath)) {
                mkdir($outputPath, 0777, true);
            }

            $fileName = path_concat($outputPath, $this->className) . $ext;
            if (file_put_contents($fileName, $content) === false) {
                Log::error("Failed save doc to $fileName");
            }
        }
    }

    private function generateTag($tagName, $content) {
        if (empty($content)) return null;

        $content = trim($content);
        return "\n// tag::{$tagName}[]\n[#$tagName]\n{$content}\n// end::{$tagName}[]\n";
    }
}
