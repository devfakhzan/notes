<?php

/* Authentication class for OOP test*/
use \Firebase\JWT\JWT;
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

class Auth {

    public $jwt;
    public $user_id;

    public function __construct($user_id)
    {
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => strtotime('now'),
            "nbf" => strtotime('now'),
            "exp" => strtotime("+5 hour"),
            "sub" => ['user_id' => $user_id]
        );

        $this->jwt = JWT::encode($payload, $_ENV['JWT_KEY']);
        $this->user_id = $user_id;
    }



    public static function isAuthenticated($payload) {
        try {
            $decoded = JWT::decode($payload, $_ENV['JWT_KEY'], array('HS256'));
            return $decoded;
        } catch (Exception $e) {
            $exception = explode("\\", get_class($e));
            return end($exception);
        } 
    }
}