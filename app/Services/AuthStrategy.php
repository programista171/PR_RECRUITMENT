<?php

namespace App\Services;

use App\Contracts\AuthStrategyInterface;

class AuthStrategy implements AuthStrategyInterface
{
    private $authService;

    public function __construct($authService)
    {
        $this->authService = $authService;
    }

    public function authenticate(string $login, string $password): array
    {
        $isAuthenticated = $this->authService->login($login, $password);

        if ($isAuthenticated) {
            $token = $this->generateToken($login);
            return ['status' => 'success', 'token' => $token];
        }

        return ['status' => 'failure', 'message' => 'Authentication failed.'];
    }

    protected function generateToken(string $login): string
    {
        // Implement JWT or any other token generation method securely
        // This is just a placeholder for illustration
        return 'token_' . bin2hex(random_bytes(8));
    }
}
