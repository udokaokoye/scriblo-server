<?php
declare(strict_types=1);
include __DIR__ . '/vendor/autoload.php';
// require_once('/Applications/XAMPP/xamppfiles/htdocs/scriblo-server/vendor/autoload.php');
include __DIR__ . '/utils/ResponseHandler.php';

use Firebase\JWT\JWT;

// require_once('../vendor/autoload.php');

class JwtUtility
{
    private static $secretKey = "SCRIBLO_SERVER_V"; // Replace with your own secret key
    private static $expirationTime = 30 * 24 * 60 * 60; // 30 days in seconds
    
    public static function generateToken($payload, $expirationTime = null)
    {
       $expirationTime = $expirationTime = strtotime($expirationTime);
        
       
       $payload['exp'] = $expirationTime;
       $jwtToken = JWT::encode($payload, self::$secretKey, 'HS256');
        return $jwtToken;
    }
    
    public static function verifyToken($jwtToken)
    {
        try {
            $decodedToken = JWT::decode($jwtToken, self::$secretKey, array('HS256'));
            
            if (isset($decodedToken->exp) && $decodedToken->exp >= time()) {
                return $decodedToken;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
    public static function verifyHttpAuthorization()
    {
        $receivedToken = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!$receivedToken) {
            // Token not provided
            echo ResponseHandler::sendResponse(401, 'Unauthorized');
            exit();
        }

        $decodedToken = self::verifyToken($receivedToken);

        if (!$decodedToken) {
            // Token verification failed or expired
            echo ResponseHandler::sendResponse(401, 'Unauthorized');
            exit();
        }

        return $decodedToken;
    }
}
