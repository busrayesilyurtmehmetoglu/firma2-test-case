<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\JwtService;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function register()
    {
        $data = $this->request->getJSON(true);
        $userModel = new UserModel();
        if ($userModel->where('email', $data['email'])->first()) {
            return $this->fail('Email already exists', 409);
        }
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $userModel->insert($data);
        return $this->respondCreated(['message' => 'User registered']);
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        $userModel = new UserModel();
        $user = $userModel->where('email', $data['email'])->first();
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->failUnauthorized('Invalid credentials');
        }
        $jwt = (new JwtService())->generate([
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
        ]);
        return $this->respond(['token' => $jwt]);
    }
} 