<?php
class ResponseHandler {
    public static function sendResponse($statusCode, $message, $data = null, $token = null) {
        // Set the HTTP response status code
        http_response_code($statusCode);

        // Set the response message
        $response = array(
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
            'token' => $token
        );

        // Convert the response to JSON format
        $jsonResponse = json_encode($response);

        // Set the content type header to JSON
        header('Content-Type: application/json');

        // Send the JSON response to JavaScript
        echo $jsonResponse;
    }
}