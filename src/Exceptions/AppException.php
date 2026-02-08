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
    private $detail;

    /**
     * ApiException constructor.
     * @param int $code
     * @param string $message
     */
    private function __construct(int $code, string $message, $detail=null) {
        parent::__construct($message, $code);
        $this->detail = $detail ?: $message;
    }

    public static function of($code, $message, $detail=null) {
        return new AppException($code, $message, $detail);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function render(Request $request) {
        Log::error("<<< Response from [{$request->getRequestUri()}] error: $this->detail");
        return \response(['code' => $this->getCode(), 'msg' => $this->getMessage()]);
    }
}
