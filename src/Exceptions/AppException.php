<?php

namespace QFrame\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * <apidoc>
 * == ApiException
 * Class ApiException is defined for the generally exception of APIs.
 * The class contains two properties `code` and `message` and supports rending itself as a JSON data.
 *
 * .Rendering Example
 * [source, json]
 * ----
 * {
 *   "code": 9000,
 *   "message": "System internal exception"
 * }
 * ----
 * </apidoc>
 * @package App\Exceptions
 */
class AppException extends \RuntimeException
{
    /**
     * ApiException constructor.
     * @param int $code
     * @param string $message
     */
    private function __construct(int $code, string $message) {
        parent::__construct($message, $code);
    }

    public static function of($code, $message) {
        return new AppException($code, $message);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function render(Request $request) {
        Log::error("<<< Response from [{$request->getRequestUri()}] error: $this->message");
        return \response(['code' => $this->getCode(), 'msg' => $this->getMessage()]);
    }
}
