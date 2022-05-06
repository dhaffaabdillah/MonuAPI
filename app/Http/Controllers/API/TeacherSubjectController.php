<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class TeacherSubjectController extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('Admin')->except('');
    }
    public function index()
    {
        $data = TeacherSubject::all();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $session = $request->user();
        if ($session) {
            $validator = Validator::make($request->all(), $this->subjectValidatedRules());
            if (!$validator->fails()) {
                $subject =  TeacherSubject::create([
                    'subject_name' => $request->subject_name,
                    'details' => $request->details,
                ]);

                return $this->onSuccess([$subject], 'Subject created');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::check()) {
            $data = TeacherSubject::with(['teacher', 'subject'])->findOrFail($id);
            if (!$data) {
                return $this->onError(404, 'Subject not found');
            }
            return $this->onSuccess([$data], 'Subject found');
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(TeacherSubject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeacherSubject $subject, $id)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), $this->subjectValidatedRules());
            if (!$validator->fails()) {
                $subject =  TeacherSubject::find($id);
                $subject->subject_name = $request->subject_name;
                $subject->detail = $request->detail;
                $subject->update();
                return $this->onSuccess([$subject], 'User updated');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeacherSubject $subject, $id)
    {
        $user = Auth::user();
        if ($user->role == 3) {
            $subject = TeacherSubject::find($id); // Find the id of the subject passed
            $subject->delete(); // Delete the specific subject data
            if (!empty($subject)) {
                return $this->onSuccess([$subject], 'Subject Deleted');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
