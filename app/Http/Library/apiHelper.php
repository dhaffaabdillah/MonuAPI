<?php
namespace App\Http\Library;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
trait apiHelper 
{
    protected function onSuccess($data, string $message = '', int $code = 200) : JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

    protected function userValidatedRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string'],
            'nis' => ['string', 'unique:users'],
            'nisn' => ['string', 'unique:users'],
        ];
    }
}