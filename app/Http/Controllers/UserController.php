<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Providers\User\UserLoginProvider;
use App\Providers\User\UserRegisterProvider;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserController extends Controller
{
    protected $userLoginProvider;
    protected $userRegisterProvider;

    public function __construct(UserLoginProvider $userLoginProvider, UserRegisterProvider $userRegisterProvider)
    {
        $this->userLoginProvider = $userLoginProvider;
        $this->userRegisterProvider = $userRegisterProvider;
    }

    public function login(UserLoginRequest $request) : JsonResponse {
        return $this->handleRequest($request, [$this->userLoginProvider, 'authenticate']);
    }

    public function register(UserRegisterRequest $request) : JsonResponse {
        return $this->handleRequest($request, [$this->userRegisterProvider, 'registerUser']);
    }

    private function handleRequest($request, callable $callback) : JsonResponse {
        try {
            $response = $callback($request->validated());
            return response()->json([$response], 200);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], $th->getCode() < 500 ? $th->getCode() : 500);
        }
    }
}
