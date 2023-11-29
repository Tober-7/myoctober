<?php namespace ApiException\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use October\Rain\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ApiResponse
{
    public static function apiErrorResponse($exception, $statusCode = null)
    {
        $message = $exception->getMessage();

        $exceptionStatusCode = 500;
        if (method_exists($exception, 'getStatusCode')) {
            $exceptionStatusCode = $exception->getStatusCode();
        } elseif (method_exists($exception, 'getCode')) {
            $exceptionStatusCode = $exception->getCode();
		}

		if (is_string($exceptionStatusCode)) {
			$exceptionStatusCode = intval($exceptionStatusCode);
		}

        if ($exceptionStatusCode < 100 || $exceptionStatusCode > 511) {
            $exceptionStatusCode = 500;
        }

        if ($exception instanceof ModelNotFoundException) {
            $message = sprintf('%s not found',
                array_last(explode('\\', $exception->getModel()))
            );
            $exceptionStatusCode = 404;
        } elseif ($exception instanceof ValidationException) {
            $message = collect($exception->getErrors())->toArray();
            $exceptionStatusCode = 422;
        } else {
            Event::fire('exception.report', [$exception]);
        }

        if ($statusCode) {
            $exceptionStatusCode = $statusCode;
        }

        $content = [
            'error'      => $message,
            'statusCode' => $exceptionStatusCode
        ];

        if (env('APP_DEBUG')) {
            $content['trace'] = $exception->getTrace();
        }

        $response = Event::fire('wezeo.api.error.response', [$content, $exception], true);
        if ($response) {
            if ($response instanceof Response) {
                return $response;
            }

            $content = $response;
        }

        return new Response($content, $content['statusCode'] ?? $exceptionStatusCode);
    }
}
