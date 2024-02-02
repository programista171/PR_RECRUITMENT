<?php

namespace App\Services;

class TokenService
{
    /**
     * Generate a simple token for authentication purposes.
     *
     * @param string $login
     * @param string $system
     * @return string
     */
    public function generateToken(string $login, string $system): string
    {
        // Simplified token generation logic
        $payload = base64_encode(json_encode([
            'login' => $login,
            'system' => $system,
            'time' => time()
        ]));

        // In real-world scenarios, consider using more secure token generation mechanisms
        return hash('sha256', $payload);
    }
}
