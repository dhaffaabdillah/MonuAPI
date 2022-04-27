<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class QuestionController extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $question = Question::with(['teachers', 'subjects'])->paginate($request->get('limit'));
        return $this->onSuccess($question, 200);
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
        $file = $request->file('file');
        $role = $request->user()->role;
        if(in_array($role, [2,3])){
            $validator = Validator::make($request->all(), $this->questionValidatedRules());
            if (!$validator->fails()) {
                if ($request->hasFile($file)) {
                    // $filename = Carbon::now()->format('d-m-Y H:i:s')."_".$file->getClientOriginalName();
                    $file->move('question-files', $file->hashName());
                }
                if(Teacher::find($request->teacher_id) == NULL) {
                    return $this->onError(404, 'Teacher not found');
                }

                if(Subject::find($request->subject_id) == NULL) {
                    return $this->onError(404, 'Subject not found');
                } 

                $question = Question::create([  
                    'teacher_id'    => $role == 3 ? $request->teacher_id : $request->user()->id->where('role', 2),
                    'subject_id'    => $request->subject_id,
                    'file_type'     => $file->getClientMimeType(),
                    'file'          => $file->hashName(),
                    'question'      => $request->question,
                    'option_a'      => $request->option_a,
                    'option_b'      => $request->option_b,
                    'option_c'      => $request->option_c,
                    'option_d'      => $request->option_d,
                    'option_e'      => $request->option_e,
                    'correct_answer'=> $request->correct_answer,
                ]);
                return $this->onSuccess($question, 'Question created successfully', 201);
                // dd($question);
            }
            return $this->onError(422, $validator->errors());
        }
        return $this->onError(403, 'Role doesnt support!');
    }

    /**k
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        $file =  $request->file('files');
        if (in_array($role, [2, 3])) {
            $validator = Validator::make($request->all(), $this->questionValidatedRules());
            if ($validator->fails()) {
               return $this->onError(400, $validator->errors());
            } else {
                $data = Question::findOrFail($id);
                $data->subject_id = $request->subject_id;
                $data->teacher_id = $request->teacher_id;
                // $data->bobot = $request->bobot;
                $data->question = $request->question;
                if($request->hasFile('files')) {
                    // $file_name = time()."_".Carbon::now()->format('d-M-Y')."_"."Subject:$request->subject_id".$file->getClientOriginalName();
                    // if($user->profile_picture != $file_name){
                    //     unlink(getcwd().$user->profile_picture);
                    // }
                    // $path = "/images/profile-pictures/$file_name";
                    // $file->move('question-file/', $file_name);
                    // $data->files = $path;
                } else {

                
                // $data->file_type = $file->getClientOriginalExtension();
                $data->option_a = $request->option_a;
                $data->option_b = $request->option_b;
                $data->option_c = $request->option_c;
                $data->option_d = $request->option_d;
                $data->option_e = $request->option_e;
                $data->update();
                return $this->onSuccess($data, "Soal berhasil diperbaharui!", 200);
                }
            }
        }
        return $this->onError(403, 'Access forbidden');
    }

    public function updates(Request $request, Question $question)
    {
        // $question = Question::find($id);
        $file = $request->file('files');
        if (in_array($request->user()->role, [2, 3])) {
            if (!$request->hasFile('files')) {
                $question->update([
                    'teacher_id'    => $request->user()->role == 3 ? $request->teacher_id : $request->user()->id,
                    'subject_id'    => $request->subject_id,
                    // 'file_type'     => $request->file->getClientMimeType(),
                    // 'files'         => $filename,
                    'question'      => $request->question,
                    'option_a'      => $request->option_a,
                    'option_b'      => $request->option_b,
                    'option_c'      => $request->option_c,
                    'option_d'      => $request->option_d,
                    'option_e'      => $request->option_e,
                    'correct_answer'=> $request->correct_answer,
                ]);
            } else {
                $file->storeAs('public/question-files', $file->hashName());
                Storage::delete('public/question-files'.$question->files);
                $question->update([
                    'teacher_id'    => $request->user()->role == 3 ? $request->teacher_id : $request->user()->id,
                    'subject_id'    => $request->subject_id,
                    'file_type'     => $file->getClientMimeType(),
                    'files'         => $file->hashName(),
                    'question'      => $request->question,
                    'option_a'      => $request->option_a,
                    'option_b'      => $request->option_b,
                    'option_c'      => $request->option_c,
                    'option_d'      => $request->option_d,
                    'option_e'      => $request->option_e,
                    'correct_answer'=> $request->correct_answer,
                ]);
            }

            return $this->onSuccess($question, 'Pertanyaan Kognitif berhasil diperbaharui!', 200);
        }
        return $this->onError(403, 'Anda tidak berhak memiliki akses ini!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role == 3) {
            $question = Question::find($id); // Find the id of the question passed
            $question->delete(); // Delete the specific question data
            if (!empty($question)) {
                return $this->onSuccess($question, 'User Deleted');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
