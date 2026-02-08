<?php namespace QFrame\Support;

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
     * VersionCommand constructor.
     */
    public function __construct() {
        $this->parseVersion();
    }

    public function get() {
        $version = Str::of("$this->major.$this->minor.$this->patch");
        if (!empty($this->preRelease)) {
            $version = $version->append("-", $this->preRelease);
        }

        if (!empty($this->build)) {
            $version = $version->append("+", $this->build);
        }

        if ($this->dirty) {
            $version = $version->append("-dirty");
        }

        return $version;
    }

    private function parseVersion() {
        // 1.0.1-2-gd000854-dirty
        $version = Str::of(exec_command_line(self::COMMAND));
        if ($version->endsWith('-dirty')) {
            $this->dirty = true;
            $version = $version->beforeLast('-');
        }

        if (preg_match('/(-g)?([a-z0-9]{7})$/i', $version, $out)) {
            $this->build = $out[2];
            if($version->length() <= 7) return;

            $version = $version->beforeLast('-')->beforeLast('-');
        }

        if(empty($version)) return;

        $arrVersion = explode('-', $version);
        if (count($arrVersion) > 1) {
            $this->preRelease = $arrVersion[1];
        }
        $arrVersion = explode('.', $arrVersion[0]);
        $this->major = $arrVersion[0];

        if (count($arrVersion) > 1) {
            $this->minor = $arrVersion[1];
        }
        if (count($arrVersion) > 2) {
            $this->patch = $arrVersion[2];
        }
    }
}