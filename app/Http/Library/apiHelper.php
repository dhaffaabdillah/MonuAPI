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
            'profile_picture' => ['image', 'mimes:jpeg,png,jpg|max:2048', 'nullable'],
        ];
    }

    protected function userUpdateValidatedRules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'nis' => ['string', 'nullable'],
            'nisn' => ['string', 'nullable'],
            'profile_picture' => ['image', 'mimes:jpeg,png,jpg|max:2048', 'nullable'],
        ];
    }

    protected function subjectValidatedRules(): array
    {
        return [
            'subject_name' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string', 'max:255'],
        ];
    }

    protected function teacherValidatedRules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'max:255', 'unique:teachers'],
            'nip' => ['string', 'max:255', 'unique:teachers'],
        ];
    }

    protected function examValidatedRules(): array
    {
        return [
            'teacher_id' => ['required', 'integer', 'max:255'],
            'subject_id' => ['required', 'string'],
            'exam_name' => ['required', 'string'],
            'total_question' => ['required', 'string'],
            'duration' =>  ['required', 'integer'],
            'type_question' =>  ['required', 'string'],
            'detail' =>  ['string'],
            'start_time' =>  ['required'],
            'end_time' =>  ['required'],
            'tokens' =>  ['required'],
        ];
    }

    protected function questionValidatedRules(): array
    {
        return [
            'teacher_id' => ['required', 'integer', 'max:255'],
            'subject_id' => ['required', 'string'],
            'file_type' => ['string'],
            'files' => ['nullable', 'mimes:jpg, png, mp3, mp4, gif'],
            'question' => ['required', 'string'],
            'option_a' =>  ['required', 'string'],
            'option_b' =>  ['required', 'string'],
            'option_c' =>  ['required', 'string'],
            'option_d' =>  ['required', 'string'],
            'option_e' =>  ['string'],
            'correct_answer' =>  ['required', 'string'],
            'total_correct' =>  ['integer'],
            'total_wrong' =>  ['integer'],
        ];
    }
}