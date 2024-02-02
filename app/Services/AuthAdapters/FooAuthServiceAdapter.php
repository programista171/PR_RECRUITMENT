<?php

namespace App\Services\AuthAdapters;

use App\Contracts\AuthServiceInterface;
use External\Foo\Auth\AuthWS as FooLoginService;
use External\Foo\Exceptions\AuthenticationFailedException;
use External\Foo\Exceptions\ServiceUnavailableException;

class FooAuthServiceAdapter implements AuthServiceInterface
{
    /**
     * The FooLoginService instance.
     *
     * @var FooLoginService
     */
    private $fooLoginService;

    /**
     * Create a new FooAuthServiceAdapter instance.
     *
     * @param  FooLoginService  $fooLoginService
     * @return void
     */
    public function __construct(FooLoginService $fooLoginService)
    {
        $this->fooLoginService = $fooLoginService;
    }

    /**
     * Authenticate a user based on login and password.
     *
     * This method adapts the FooLoginService's exception-throwing behavior
     * into the boolean return expected by AuthServiceInterface.
     *
     * @param string $login The user's login identifier.
     * @param string $password The user's password.
     * @return bool True if authentication is successful, false otherwise.
     */
    public function authenticate(string $login, string $password): bool
    {
        try {
            $this->fooLoginService->authenticate($login, $password);
            return true;
        } catch (AuthenticationFailedException $e) {
            // Log the authentication failed exception for auditing purposes
            \Log::warning("Authentication failed for user {$login} using FooAuthService: " . $e->getMessage());
            return false;
        } catch (ServiceUnavailableException $e) {
            // Log the service unavailable exception to investigate service issues
            \Log::error("FooAuthService unavailable for user {$login}: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            // Log unexpected exceptions to catch issues not covered by the known exceptions
            \Log::error("Unexpected error during FooAuthService authentication for user {$login}: " . $e->getMessage());
            return false;
        }
    }
}
