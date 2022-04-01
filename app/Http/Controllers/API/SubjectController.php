<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class SubjectController extends Controller
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
        $data = Subject::all();
        return $this->onSuccess($data, 200);
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
            if ($validator->passes()) {
                $subject =  Subject::create([
                    'subject_name' => $request->subject_name,
                    'details' => $request->details,
                ]);

                return $this->onSuccess($subject, 'Subject created');
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
            $data = Subject::findOrFail($id);
            if (!$data) {
                return $this->onError(404, 'Subject not found');
            }
            return $this->onSuccess($data, 'Subject found');
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
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
    public function update(Request $request, Subject $subject, $id)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), $this->subjectValidatedRules());
            if ($validator->passes()) {
                $subject =  Subject::find($id);
                $subject->subject_name = $request->subject_name;
                $subject->detail = $request->detail;
                $subject->update();
                return $this->onSuccess($subject, 'User updated');
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
    public function destroy(Subject $subject, $id)
    {
        $user = Auth::user();
        if ($user->role == 3) {
            $subject = Subject::find($id); // Find the id of the subject passed
            $subject->delete(); // Delete the specific subject data
            if (!empty($subject)) {
                return $this->onSuccess($subject, 'Subject Deleted');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
