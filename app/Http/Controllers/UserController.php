<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Providers\User\UserLoginProvider;
use App\Providers\User\UserRegisterProvider;
use App\Providers\User\UserUpdateProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UserController extends Controller
{
    protected $userLoginProvider;
    protected $userRegisterProvider;
    protected $userUpdateProvider;

    public function __construct(UserLoginProvider $userLoginProvider, UserRegisterProvider $userRegisterProvider, UserUpdateProvider $userUpdateProvider)
    {
        $this->userLoginProvider = $userLoginProvider;
        $this->userRegisterProvider = $userRegisterProvider;
        $this->userUpdateProvider = $userUpdateProvider;
    }

    public function login(UserLoginRequest $request) : JsonResponse {
        return $this->handleRequest($request, [$this->userLoginProvider, 'authenticate']);
    }

    public function register(UserRegisterRequest $request) : JsonResponse {
        return $this->handleRequest($request, [$this->userRegisterProvider, 'registerUser']);
    }

    public function logout(Request $request ) : JsonResponse {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }

    public function getUser(Request $request) : JsonResponse {
        return response()->json($request->user(), 200);
    }

    public function update(UserUpdateRequest $request) : JsonResponse {
        return $this->handleRequest($request, [$this->userUpdateProvider, 'update']);
    }

    public function changePassword(ChangePasswordRequest $request) : JsonResponse {
        return $this->handleRequest($request, [$this->userUpdateProvider, 'changePassword']);
    }

    public function forgotPassword(ForgotPasswordRequest $request) : JsonResponse {
        return $this->handleRequest($request, [$this->userLoginProvider, 'forgotPassword']);
    }

    private function handleRequest($request, callable $callback) : JsonResponse {
        try {
            $response = $callback($request->validated());
            return response()->json($response, 200);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], $th->getCode() < 500 ? $th->getCode() : 500);
        }
    }
}
