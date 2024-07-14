<?php

namespace App\Packages\JWT;

use App\Models\User;

readonly final class JWT
{
    private array $headers;
    private string $secret;

    public function __construct()
    {
        $this->headers = config('jwt.headers');
        $this->secret = config('jwt.secret');
    }

    public function setup(User $user): array
    {
        return [
            'access_token' => $this->generateJWT(user: $user, exp: config('jwt.access_token')),
            'refresh_token' => $this->generateJWT(user: $user, exp: config('jwt.refresh_token')),
        ];
    }

    private function generateJWT(User $user, int $exp): string
    {
        $claim = [
            'user_id' => $user->getAttribute('id'),
            'exp' => now()->addSeconds($exp),
        ];
        $headers_encoded = $this->base64url_encode(str: json_encode(value: $this->headers));

        $claim_encoded = $this->base64url_encode(str: json_encode(value: $claim));

        $signature = hash_hmac(algo: 'SHA256', data: "$headers_encoded.$claim_encoded", key: $this->secret, binary: true);

        $signature_encoded = $this->base64url_encode(str: $signature);

        return "$headers_encoded.$claim_encoded.$signature_encoded";
    }

    private function base64url_encode($str): string
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    public function isValid(string $jwt): bool
    {
        if (empty($jwt)){
            return false;
        }
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) !== 3){
            return false;
        }
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        if (!json_validate($payload)){
            return false;
        }

        $expiration = json_decode($payload)->exp;

        $is_token_expired = now()->gt($expiration);

        $base64_url_header = $this->base64url_encode($header);
        $base64_url_payload = $this->base64url_encode($payload);
        $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $this->secret, true);
        $base64_url_signature = $this->base64url_encode($signature);

        $is_signature_valid = ($base64_url_signature === $signature_provided);

        if ($is_token_expired || !$is_signature_valid) {
            return false;
        }
        return true;
    }

    public function getClaim(string $jwt): array
    {
        $tokenParts = explode('.', $jwt);
        $payload = base64_decode($tokenParts[1]);
        return json_decode($payload, true);
    }
}
