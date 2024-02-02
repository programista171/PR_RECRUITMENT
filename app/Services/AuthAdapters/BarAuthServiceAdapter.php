<?php

namespace App\Services\AuthAdapters;

use App\Contracts\AuthServiceInterface;
use External\Bar\Auth\LoginService as BarLoginService;

class BarAuthServiceAdapter implements AuthServiceInterface
{
    private $barLoginService;

    public function __construct(BarLoginService $barLoginService)
    {
        $this->barLoginService = $barLoginService;
    }

    public function authenticate(string $login, string $password): bool
    {
        // Directly return the result of the Bar service's login method
        return $this->barLoginService->login($login, $password);
    }
}
