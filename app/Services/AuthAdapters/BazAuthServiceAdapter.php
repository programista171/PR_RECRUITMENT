<?php

namespace App\Services\AuthAdapters;

use App\Contracts\AuthServiceInterface;
use External\Baz\Auth\Authenticator as BazAuthenticator;
use External\Baz\Auth\Responses\Success;
use External\Baz\Auth\Responses\Failure;

class BazAuthServiceAdapter implements AuthServiceInterface
{
    /**
     * @var BazAuthenticator
     */
    private $bazAuthenticator;

    /**
     * BazAuthServiceAdapter constructor.
     *
     * @param BazAuthenticator $bazAuthenticator The authenticator instance for BAZ authentication.
     */
    public function __construct(BazAuthenticator $bazAuthenticator)
    {
        $this->bazAuthenticator = $bazAuthenticator;
    }

    /**
     * Authenticate a user based on login and password for BAZ system.
     *
     * @param string $login The user's login identifier.
     * @param string $password The user's password.
     * @return bool True if authentication is successful, false otherwise.
     */
    public function authenticate(string $login, string $password): bool
    {
        $response = $this->bazAuthenticator->auth($login, $password);

        // Check the type of response to determine the authentication outcome
        if ($response instanceof Success) {
            return true;
        } elseif ($response instanceof Failure) {
            return false;
        }

        // Consider logging or handling unexpected response types if necessary
        return false;
    }
}
