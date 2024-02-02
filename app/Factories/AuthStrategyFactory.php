<?php

namespace App\Factories;

use App\Contracts\AuthServiceInterface;
use App\Services\AuthAdapters\BarAuthServiceAdapter;
use App\Services\AuthAdapters\FooAuthServiceAdapter;
use App\Services\AuthAdapters\BazAuthServiceAdapter;
use External\Bar\Auth\LoginService as BarLoginService;
use External\Foo\Auth\AuthWS as FooAuthWS;
use External\Baz\Auth\Authenticator as BazAuthenticator;
use InvalidArgumentException;

class AuthStrategyFactory
{
    /**
     * Create an authentication service adapter based on the user's login prefix.
     * 
     * @param string $login User login that includes a prefix indicating the company.
     * @return AuthServiceInterface Returns an instance of an adapter that implements AuthServiceInterface.
     * @throws InvalidArgumentException If the login prefix does not match any known company.
     */
    public static function make(string $login): AuthServiceInterface
    {
        if (strpos($login, 'FOO_') === 0) {
            $service = new FooAuthServiceAdapter(new FooAuthWS());
        } elseif (strpos($login, 'BAR_') === 0) {
            $service = new BarAuthServiceAdapter(new BarLoginService());
        } elseif (strpos($login, 'BAZ_') === 0) {
            $service = new BazAuthServiceAdapter(new BazAuthenticator());
        } else {
            throw new InvalidArgumentException("Login prefix does not match any known company.");
        }

        return $service;
    }
}
