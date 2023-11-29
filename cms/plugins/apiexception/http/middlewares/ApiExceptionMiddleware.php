<?php namespace ApiException\Http\Middlewares;

use Closure;
use ValidationException;
use ApiException\Traits\ApiResponse;

class ApiExceptionMiddleware
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $response = $next($request);

        if (!isset($response->exception) && !isset($response->original)) {
            return $response;
        }

        $exception = $response->exception ?? null;

        if (!$exception && isset($response->original['status']) && $response->original['status'] == '422'){
            $exception = new ValidationException(($response->original['error']->getMessages()));
        }
        if (!$exception && $response->original instanceof \Exception) {
            $exception = $response->original;
        }

        if ($exception) {
            return $this->apiErrorResponse($exception);
        }

        return $response;
    }
}
