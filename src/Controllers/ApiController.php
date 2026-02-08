<?php

namespace QFrame\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use QFrame\Exceptions\AppException;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function callAction($method, $parameters) {
        Log::info('>>> API Request ' . request()->getRequestUri() . ': ', request()->all());
        try {
            $response = parent::callAction($method, $parameters);
            Log::info('<<< API Response from ' . request()->getRequestUri() . ': ' . $response->getContent());
            return $response;
        } catch (AppException $exception) {
            Log::error('<<< API Response AppException from ' . request()->getRequestUri() . ': ' . $exception->getMessage());
            throw $exception;
        } catch (\Exception $exception) {
            Log::error('<<< API Response Exception from ' . request()->getRequestUri() . ': ' . $exception->getMessage());
            throw AppException::of(ERR_APP_EXCEPTION, $exception->getMessage());
        }
    }

}
