<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\AuthStrategyFactory;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use App\Services\TokenService;

class AuthController extends Controller
{
    protected $tokenService;
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function login(Request $request): JsonResponse
    {
        $login = $request->input('login');
        $password = $request->input('password');

        try {
            $authStrategy = AuthStrategyFactory::make($login);
            $isAuthenticated = $authStrategy->authenticate($login, $password);

            if ($isAuthenticated) {
                // Determine the system based on login prefix for token generation
                $system = strtoupper(explode('_', $login)[0]); // Assuming login format is PREFIX_Username
                $token = $this->tokenService->generateToken($login, $system);

                return response()->json(['status' => 'success', 'token' => $token]);
            }

            return response()->json(['status' => 'failure'], 401);

        } catch (InvalidArgumentException $e) {
            return response()->json(['status' => 'failure', 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            \Log::error("Unexpected error during authentication: " . $e->getMessage());
            return response()->json(['status' => 'failure', 'message' => 'An unexpected error occurred.'], 500);
        }
    }
}