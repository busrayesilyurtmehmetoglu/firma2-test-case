<?php

namespace App\Libraries;

class JwtService
{
    protected string $secret;
    protected int $ttl = 3600; // 1 saat

    public function __construct()
    {
        $this->secret = getenv('JWT_SECRET') ?: 'supersecretkey';
    }

    public function generate(array $payload): string
    {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + $this->ttl;
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", $this->secret, true);
        $signature = base64_encode($signature);
        return "$header.$payload.$signature";
    }

    public function validate(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        [$header, $payload, $signature] = $parts;
        $validSig = base64_encode(hash_hmac('sha256', "$header.$payload", $this->secret, true));
        if (!hash_equals($validSig, $signature)) return null;
        $data = json_decode(base64_decode($payload), true);
        if (!$data || ($data['exp'] ?? 0) < time()) return null;
        return $data;
    }
} 