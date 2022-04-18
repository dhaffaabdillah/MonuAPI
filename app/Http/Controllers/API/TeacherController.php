<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class TeacherController extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $data = Teacher::with(['user'])->paginate($request->get('limit'));
        return $this->onSuccess([$data], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getDataUserOnlyTeacher()
    {
        $data = User::where('role', 2)->get();
        return $this->onSuccess($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $session = $request->user();
        $user_id = User::select('id');
        if ($session) {
            $validator = Validator::make($request->all(), $this->teacherValidatedRules());
            if (!$validator->fails()) {
                $teacher =  Teacher::create([
                    'user_id' => $request->user_id,
                    'teacher_name' => User::select('name')->where(
                        [['id', "=",$request->user_id]],
                        // ['role', "=", 2]
                        )->first()->name,

                    // 'teacher_name' => User::select('name')->where('id', $request->user_id)->first(),
                    'nip' => $request->nip,
                ]);

                return $this->onSuccess([$teacher], 'teacher created');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Teacher::findOrFail($id)->get();
        return $this->onSuccess($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teachers, $id)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), $this->teacherValidatedRules());
            if (!$validator->fails()) {
                if(User::select('id')->where('role', 3)->get() === $request->user_id){
                    $teacher =  Teacher::findOrFail($id);
                    $teacher->user_id = $request->user_id;
                    $teacher->teacher_name = User::select('name')->where(
                        [['id', "=",$request->user_id]],
                        )->first()->name;
                    $teacher->nip = $request->nip;
                    $teacher->update();
                    return $this->onSuccess($teacher, 'Teacher updated');
                }
                return $this->onError(404, 'Teacher not found');
                
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized');
    }

    public function updates(Request $request, Teacher $teacher, $id)
    {
        if (Auth::user()->role == 3 && Auth::check()) {
            $teachers = Teacher::find($teacher)->update([
                'user_id' => $request->user_id,
                'teacher_name' => User::select('name')->where(
                    [['id', "=",$request->user_id]]
                    )->first()->name,
                'nip'       => $request->nip,
            ]);

            return $this->onSuccess($teachers, 'Data Success', 200);
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role == 3) {
            // $teacher = Teacher::findOrFail($id); // Find the id of the tea$teacher passed
            $teacher = Teacher::where('id', $id);
            $teacher->delete(); // Delete the specific tea$teacher data
            if (!empty($teacher)) {
                return $this->onSuccess($teacher, 'Teacher Deleted');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
