<?php namespace QFrame\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Version
{
    private $major = 0;
    private $minor = 0;
    private $patch = 0;
    private $preRelease = null;

    private $build = null;

    private $dirty = false;

    private const COMMAND = 'git describe --always --tags --dirty';

    /**
     * Version constructor.
     */
    public function __construct() {
        $this->parseVersion();
    }


    public function full() {
        $version = Str::of("$this->major.$this->minor.$this->patch");
        if (!empty($this->preRelease)) {
            $version->append("-", $this->preRelease);
        }

        if (!empty($this->build)) {
            $version->append("+", $this->build);
        }

        if ($this->dirty) {
            $version->append("-dirty");
        }

        return $version;
    }

    private function parseVersion($version = null) {
        // 1.0.1-2-gd000854-dirty
        $version = Str::of(exec_command_line(self::COMMAND));
        Log::debug("Get version from GIT: $version");
        if ($version->endsWith('-dirty')) {
            $this->dirty = true;
            $version = $version->beforeLast('-');
        }

        if ($version->match('/(-g)?([a-z0-9]{7})$/')) {

        }
    }
}