<?php

namespace App\Filters;

use App\Libraries\JwtService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Exceptions\UnauthorizedException;

class JwtAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(.*)/', $authHeader, $matches)) {
            throw new UnauthorizedException('JWT token required');
        }
        $token = $matches[1];
        $jwt = new JwtService();
        $payload = $jwt->validate($token);
        if (!$payload) {
            throw new UnauthorizedException('Invalid or expired JWT token');
        }
        // Kullanıcı bilgilerini request'e ekle
        $request->user = $payload;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after
    }
} 