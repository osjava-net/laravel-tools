<?php

use QFrame\Exceptions\ApiException;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Symfony\Component\Finder\Finder;

if (!function_exists('path_concat')) {
    /**
     * Concat the two paths $basePath and $subPath.
     *
     * @param string $basePath
     * @param string|array $subPath
     * @return string
     */
    function path_concat($basePath, $subPath) {
        $fullPath = $basePath;
        if (is_string($subPath)) {
            $fullPath .= '/' . trim($subPath, '/');
        } else {
            foreach ($subPath as $item) {
                $fullPath .= '/' . trim($item, '/');
            }
        }

        return $fullPath;
    }
}

if (!function_exists('path_finder')) {
    /**
     * Build a Symfony Finder object that scans the given $directory.
     *
     * @param string|array|Finder $directory The directory(s) or filename(s)
     * @param null|string|array $exclude The directory(s) or filename(s) to exclude (as absolute or relative paths)
     * @param null|string $pattern The pattern of the files to scan
     * @return Finder|array|string
     * @throws InvalidArgumentException
     */
    function path_finder($directory, $exclude = null, $pattern = null) {
        if ($directory instanceof Finder) {
            return $directory;
        } else {
            $finder = new Finder();
            $finder->sortByName();
        }
        if ($pattern === null) {
            $pattern = '*.php';
        }

        $finder->files()->followLinks()->name($pattern);
        if (is_string($directory)) {
            if (is_file($directory)) { // Scan a single file?
                $finder->append([$directory]);
            } else { // Scan a directory
                $finder->in($directory);
            }
        } else if (is_array($directory)) {
            foreach ($directory as $path) {
                if (is_file($path)) { // Scan a file?
                    $finder->append([$path]);
                } else {
                    $finder->in($path);
                }
            }
        } else {
            throw new InvalidArgumentException('Unexpected $directory value:' . gettype($directory));
        }
        if ($exclude !== null) {
            if (is_string($exclude)) {
                $finder->notPath(get_relative_path($exclude, $directory));
            } else if (is_array($exclude)) {
                foreach ($exclude as $path) {
                    $finder->notPath(get_relative_path($path, $directory));
                }
            } else {
                throw new InvalidArgumentException('Unexpected $exclude value:' . gettype($exclude));
            }
        }
        return $finder;
    }
}

if (!function_exists('get_relative_path')) {
    /**
     * Turns the given $fullPath into a relative path based on $basePaths, which can either
     * be a single string path, or a list of possible paths. If a list is given, the first
     * matching basePath in the list will be used to compute the relative path. If no
     * relative path could be computed, the original string will be returned because there
     * is always a chance it was a valid relative path to begin with.
     *
     * It should be noted that these are "relative paths" primarily in Finder's sense of them,
     * and conform specifically to what is expected by functions like `exclude()` and `notPath()`.
     * In particular, leading and trailing slashes are removed.
     *
     * @param string $fullPath
     * @param string|array $basePaths
     * @return string
     */
    function get_relative_path($fullPath, $basePaths) {
        $relativePath = null;
        if (is_string($basePaths)) { // just a single path, not an array of possible paths
            $relativePath = path_prefix_remove($fullPath, $basePaths);
        } else { // an array of paths
            foreach ($basePaths as $basePath) {
                $relativePath = path_prefix_remove($fullPath, $basePath);
                if (!empty($relativePath)) {
                    break;
                }
            }
        }
        return !empty($relativePath) ? trim($relativePath, '/') : $fullPath;
    }
}

if (!function_exists('path_prefix_remove')) {
    /**
     * Removes a prefix from the start of a string if it exists, or null otherwise.
     *
     * @param string $str
     * @param string $prefix
     * @return null|string
     */
    function path_prefix_remove($str, $prefix) {
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            return substr($str, strlen($prefix));
        }
        return null;
    }
}

if (!function_exists('is_json')) {
    function is_json($str) {
        json_decode($str);
        return JSON_ERROR_NONE === json_last_error();
    }
}

if (!function_exists('is_not_json')) {
    function is_not_json($str) {
        json_decode($str);
        return JSON_ERROR_NONE !== json_last_error();
    }
}

if (!function_exists('get_request_param')) {
    function get_request_param($key, $default = null) {
        $value = request($key);
        if (empty($value) && func_num_args() < 2) throw exp_invalid_param($key);

        return $value ?? $default;
    }
}

if (!function_exists('api_success')) {
    /**
     * @param null|mixed $data
     * @return JsonResponse
     */
    function api_success($data = null) {
        $result = ['code' => SUCCESS_CODE, 'msg' => 'Success'];
        if (isset($data)) $result['data'] = $data;

        return response()->json($result);
    }
}

if (!function_exists('api_error')) {
    /**
     * @param int $code
     * @param string $message
     * @param null|mixed $data
     * @return JsonResponse
     */
    function api_error($code, $message, $data = null) {
        $result = ['code' => $code, 'msg' => $message,];
        if (!empty($data)) $result['data'] = $data;

        return response()->json($result);
    }
}

if (!function_exists('api_exception')) {
    /**
     * @param ApiException $exception
     * @param null|mixed $data
     * @return JsonResponse
     */
    function api_exception($exception, $data = null) {
        $result = ['code' => $exception->getCode(), 'msg' => $exception->getMessage(),];
        if (!empty($data)) $result['data'] = $data;

        return response()->json($result);
    }
}

if (!function_exists('exp_invalid_request')) {
    function exp_invalid_request($url, $message = null) {
        if (is_null($message)) {
            return ApiException::of(ERR_REQUEST_EXCEPTION, "Request [$url] is invalid");
        }

        return ApiException::of(ERR_REQUEST_EXCEPTION, "Request [$url] has an error: $message");
    }
}

if (!function_exists('exp_invalid_param')) {
    function exp_invalid_param($key) {
        return ApiException::of(ERR_INVALID_PARAM, "Not found the parameter[$key] or its value is NULL.");
    }
}

if (!function_exists('exp_not_found_sign_parameter')) {
    function exp_not_found_sign_parameter() {
        return ApiException::of(ERR_INVALID_SIGN, "Not found the api sign parameter");
    }
}

if (!function_exists('exp_expired_sign_time')) {
    function exp_expired_sign_time($sign, $timer) {
        $strTimer = Carbon::createFromTimestamp($timer)->toDateTimeString();
        return ApiException::of(ERR_EXPIRED_SIGN, "The sign[$sign] was generated by $strTimer and has expired.");
    }
}

if (!function_exists('exp_invalid_sign_value')) {
    function exp_invalid_sign_value($sign) {
        return ApiException::of(ERR_INVALID_SIGN, "The sign[$sign] is invalid.");
    }
}

if (!function_exists('exp_not_found_application_key')) {
    function exp_not_found_application_key($key) {
        return ApiException::of(ERR_INVALID_APPLICATION, "Not found the application bye the key[$key]");
    }
}

if (!function_exists('exp_disabled_application')) {
    function exp_disabled_application($key) {
        return ApiException::of(ERR_DISABLED_APPLICATION, "The application[$key] is disabled");
    }
}
