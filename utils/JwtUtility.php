<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../utils/ResponseHandler.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtUtility
{
    public static $secretKey = "SCRIBLO_SERVER_V"; // Replace with your own secret key
    private static $expirationTime = 30 * 24 * 60 * 60; // 30 days in seconds

    public static function generateToken($payload, $expirationTime = "+30 days")
    {
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify($expirationTime)->getTimestamp(); 
        // $serverName = "http://localhost:3000";


        $data = [
            'iat'  => $issuedAt->getTimestamp(),               // Issuer
            'nbf'  => $issuedAt->getTimestamp(),         // Not before
            'exp'  => $expire,                           // Expire
            'data' => $payload,                     // User name
        ];

        return JWT::encode($data, self::$secretKey, "HS512");
        // return $jwtToken;
    }

    public static function verifyToken($jwtToken)
    {
        $jwtToken = str_replace('Bearer ', '', $jwtToken);
        // die($jwtToken);
        // return;
        try {
            $decodedToken = JWT::decode($jwtToken, new Key(self::$secretKey, 'HS512'));

            if (isset($decodedToken->exp) && $decodedToken->exp >= time()) {
                return $decodedToken;
            } else {
                die('Token expired');
                return false;
            }
        } catch (Exception $e) {
            die("Exception: {$e->getMessage()}");
            return false;
        }
    }

    public static function verifyHttpAuthorization()
    {
        $receivedToken = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!$receivedToken) {
            // Token not provided
            echo ResponseHandler::sendResponse(401, 'NO_TOKEN_PROVIDED');
            exit();
        }

        $decodedToken = self::verifyToken($receivedToken);

        if (!$decodedToken) {
            // Token verification failed or expired
            echo ResponseHandler::sendResponse(401, 'Unauthorized access');
            exit();
        }

        return $decodedToken;
    }
}
