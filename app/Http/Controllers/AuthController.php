<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    /**
     * Create Users and Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:5|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ]);
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        try {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = app('hash')->make($password);

            if ($user->save()) {
                return $this->login($request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);
        $email = $request->email;
        $password = $request->password;


        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JSON
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Update user existing profile
     *
     * @return JSON
     */
    public function updateProfile(Request $request)
    {
        $user = auth() -> user();

        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required|min:5|max:255',
        ]);
        
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        $user->name = $name;
        $user->email = $email;
        if($password)
            $user->password = app('hash')->make($password);
        $user->save();
        return $user;
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return JSON
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
