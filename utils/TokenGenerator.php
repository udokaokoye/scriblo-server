<?php
// class that generates a 5 integer digit token
class TokenGenerator {
    public static function generateToken() {
        $token = '';
        for ($i = 0; $i < 5; $i++) {
            $token .= rand(0, 9);
        }
        return $token;
    }
}
