<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'string|required',
            'email' => 'string|required|email|unique:users',
            'gender' => 'string|required',
            'class' => 'string|required',
            'nik' => 'integer|required',
            'password' => 'string|required|min:8|confirmed',
        ]);

        if ($validate->fails()) {
            $respon = [
                'success' => false,
                'msg' => 'Validator error',
                'errors' => [
                    $validate->errors()
                ],
                'content' => null,
            ];
            return response()->json($respon, 200);
        } else  {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'class' => $request->class,
                'nik' => $request->nik,
                'gender' => $request->gender,
            ]);
            return response()->json(['msg' => 'Registration successful!', 'response' => $user], 200);
        }
    }

    public function login(Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            $respon = [
                'success' => false,
                'msg' => 'Validator error',
                'errors' => [
                    $validate->errors()
                ],
                'content' => null,
            ];
            return response()->json($respon, 200);
        } else {
            $credentials = request(['email', 'password']);
            $credentials = Arr::add($credentials, 'status', 'Active');
            if (!Auth::attempt($credentials)) {
                $respon = [
                    'success' => false,
                    'msg' => 'Unathorized',
                    'errors' => null,
                    'content' => null,
                ];
                return response()->json($respon, 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            $tokenResult = $user->createToken('token-auth')->plainTextToken;
            $respon = [
                'success' => true,
                'msg' => 'Login successfully',
                'errors' => null,
                'content' => [
                    [
                        'status_code' => 200,
                        'access_token' => $tokenResult,
                        'token_type' => 'Bearer',
                    ]
                ]
            ];
            return response()->json($respon, 200);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $response = [
            'success' => true,
            'message' => 'Logout successful',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($response, 200);
    }

    public function logoutAll(Request $request)
    {
        if (Auth::user()->role == 3) {
            $user = $request->user();
            $user->tokens()->delete();
            $response = [
                'success' => true,
                'message' => 'Logout successful',
                'errors' => null,
                'content' => null,
            ];
            return response()->json($response, 200);
        } else {
            return response()->json(['message' => 'Access denied due the roles doesnt allowed'], 401);
        }

    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)]
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function($user) use ($request)
            {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(32),
                ])->save();

                $user->tokens()->delete();
                event(new PasswordReset($user));
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message' => 'Password reset successfully!'
            ]);
        }

        return response(['message' => __($status)], 500);
    }

    public function getCurrentSession()
    {
        return response()->json(Auth::user(), 200);
    }

}
