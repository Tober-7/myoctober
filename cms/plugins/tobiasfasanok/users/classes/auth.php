<?php namespace Tobiasfasanok\Users\Classes;

use Illuminate\Http\Request;
use Closure;
use Exception;

use Tobiasfasanok\Users\Models\User;

class Auth {
    public function handle(Request $request, Closure $next) {
        $response = $next($request);

        $tokenExists = User::where('token', $request->bearerToken());

        if (!$tokenExists) {
            throw new Exception("Invalid authorization token.", 500);
        }

        return $response;
    }
    
    public static function generateJWT($id) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['user_id' => $id]);
    
        $base64UrlHeader = base64_encode($header);
        $base64UrlPayload = base64_encode($payload);
    
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, '!2&#@%?', true);
        $base64UrlSignature = base64_encode($signature);
    
        return $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
    
    public static function readJWT($token) {
        $payload = explode('.', $token)[1];
        $object = base64_decode($payload);
        return json_decode($object)->user_id;
    }
}

?>