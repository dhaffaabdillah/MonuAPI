<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExamController extends Controller
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
        // $this->middleware('Admin')->except('');
    }
    public function index(Request $request)
    {
        $teacher = DB::raw('SELECT user_id FROM teachers JOIN users ON users.id = teachers.user_id WHERE teachers.user_id', Auth::user()->id);
        // $data = Exam::where('teacher_id', $teacher)->get();
        $data = Exam::all();
        return view('admin.exam.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.exam.create',compact('data'));
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
            $validator = Validator::make($request->all(), $this->examValidatedRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors());
            } else {
                $exam =  Exam::create([
                    'teacher_id' => $request->teacher_id,
                    'subject_id' => $request->subject_id,
                    'exam_name' => $request->exam_name,
                    'total_question' => $request->total_question,
                    'type_question' => $request->type_question, // random atau berurutan
                    'duration' => $request->duration, // in minutes
                    'start_time' => $request->start_time, // in timestamps
                    'end_time' => $request->end_time, // in timestamos 
                    'tokens' => Str::random(5),
                ]);
                return redirect()->url('/');
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
            $data = Exam::findOrFail($id);
            if (!$data) {
                return $this->onError(404, 'exam not found');
            }
            return $this->onSuccess([$data], 'exam found');
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.exam-package.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), $this->examValidatedRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors());
                
            } else {
                $exam =  Exam::findOrFail($id);
                $exam->teacher_id = $request->teacher_id;
                $exam->subject_id = $request->subject_id;
                $exam->exam_name = $request->exam_name;
                $exam->total_question = $request->total_question;
                $exam->details = $request->details;
                $exam->duration = $request->duration;
                $exam->type_question = $request->type_question;
                $exam->tokens = Str::random(5);
                $exam->start_time = $request->start_time;
                $exam->end_time= $request->end_time;
                $exam->update();
                return redirect()->route('e-index');
                // return $this->onSuccess([$exam], 'Exam updated');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        // if ($user->role == 3) {
            $exam = Exam::find($id); // Find the id of the exam passed
            $exam->delete();
            return redirect()->route('e-index');
    }
}
