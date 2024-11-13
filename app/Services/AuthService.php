<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{

    /**
     * service to create user and store data in storage.
     * @param array $data
     * @return array 

     */
    public function createUser(array $data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),

            ]);
            // $user->password = $data['password'];
            // $user->save();
            $token = Auth::login($user);
            return [
                'message' => 'user created successfully',
                'userData' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'there is something wrong in server'], status: 500));
        }
    }
    //==========================
    /**
     * service to login user.
     * @param User $user
     * @param array $data
     * @return array 

     */
    public function loginUser(array $credentials)
    {
        try {
            $token = Auth::attempt($credentials);
            if (!$token) {
                return [
                    'status' => 401,
                    'message' => 'Unauthorized',
                    'error' => 'error',
                ];
            }
            $user = Auth::user();
            return [
                'message' => 'success',
                'userData' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error('Error login user: ' . $e->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'there is something wrong in server'], status: 500));
        }
    }
    /**
     * service to logout user.
     * @return array 

     */
    public function logoutUser()
    {
        try {
            Auth::logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            return ['message' => 'Successfully logged out', 'status' => 200];
        } catch (Exception $e) {
            Log::error('Error logout user: ' . $e->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'there is something wrong in server'], status: 500));
        }
    }
}
