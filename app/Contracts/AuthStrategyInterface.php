<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    /**
     * Authenticate a user based on login and password.
     *
     * @param string $login The user's login identifier.
     * @param string $password The user's password.
     * @return bool True if authentication is successful, false otherwise.
     */
    public function authenticate(string $login, string $password): bool;
}
