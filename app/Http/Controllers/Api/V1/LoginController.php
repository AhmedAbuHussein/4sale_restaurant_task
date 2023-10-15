<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use Exception;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends Controller
{
    
    public function login(LoginRequest $request)
    {
        $user = User::where(['email'=> $request->email])->first();
        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken(Str::random(10))->plainTextToken;
            return response()->json([
                "data"=> [
                    "token"=> $token,
                    "user"=> new UserResource($user),
                ],
            ]);
        }
       // throw new Exception("Invalida User Credintials", 400);
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => "Logged Out"]);
    }

    public function info(Request $request)
    {
        $user = $request->user();
        return response()->json([
            "data"=> [
                "user"=> new UserResource($user),
            ],
        ]);
    }

}
