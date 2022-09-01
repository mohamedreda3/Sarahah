<?php
require __DIR__ . '/../' . 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class GetToken
{
    private $aud = '/';
    private $accessKey = '0c73cc64004aba3a3bf660f276fa4144';
    private $alg = 'HS256';
    private array $payload = [];
    public function processPayload(?string $email, ?string $aud = '/')
    {
        $this->aud = $aud;
        $this->payload = array(
            'iss' => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'],
            'aud' => $this->aud,
            'iat' => time(),
            'exp' => time() + 86400 * 5,
            'data' => base64_encode($email),
        );
    }
    public function exportAccessToken(?string $aud = '/')
    {
        $accessJwt = JWT::encode($this->payload, $this->accessKey, $this->alg);
        return $accessJwt;
    }

    public function decodeAccessToken($Token)
    {
        return json_decode(json_encode(JWT::decode($Token, new Key($this->accessKey, $this->alg))), true);
    }
}
