<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class AccessDeniedException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        Log::debug('Access Denied');
    }

    public function render($request)
    {
        return response()->json(['error' => 'Unregistered user'], Response::HTTP_UNAUTHORIZED);
    }
}