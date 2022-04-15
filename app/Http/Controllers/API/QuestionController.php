<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Validator;

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
        $question = Question::paginate($request->get('limit'));
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
        $role = Auth::user()->role;
        $file = $request->file('files');
        if (in_array($role, [2, 3])) {
            $validator = Validator::make($request->all(), $this->questionValidatedRules());
            if ($validator->fails()) {
               return $this->onError(400, $validator->errors());
            } else {
                if(!is_null($file)) {
                    $file_name = time()."_".Carbon::now()->format('d-M-Y')."_"."Subject:$request->subject_id".$file->getClientOriginalName();
                    // if($user->profile_picture != $file_name){
                    //     unlink(getcwd().$user->profile_picture);
                    // }
                    $path = "/file/question-file/$file_name";
                    $file->move('question-file/', $file_name);
                }
                $question = Question::create([
                    'teacher_id' => $request->teacher_id,
                    'subject_id' => $request->subject_id,
                    'file_type' => $request->file_type->getClientMimeType(),
                    'files' => $path,
                    'question' => $request->question,
                    'option_a' => $request->option_a,
                    'option_b' => $request->option_b,
                    'option_c' => $request->option_c,
                    'option_d' => $request->option_d,
                    'option_e' => $request->option_e,
                    'correct_answer' => $request->correct_answer,
                ]);

                return $this->onSuccess($question, 201);
            }
        }
        return $this->onError(403, 'Access forbidden');
    }

    /**
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
    public function update(Request $request, Question $question)
    {
        $role = Auth::user()->role;
        $file =  $request->file('files');
        if (in_array($role, [2, 3])) {
            $validator = Validator::make($request->all(), $this->questionValidatedRules());
            if ($validator->fails()) {
               return $this->onError(400, $validator->errors());
            } else {
                $data = Question::findOrFail($question);
                $data->subject_id = $request->subject_id;
                $data->teacher_id = $request->teacher_id;
                $data->bobot = $request->bobot;
                $data->soal = $request->soal;
                if(!is_null($file)) {
                    $file_name = time()."_".Carbon::now()->format('d-M-Y')."_"."Subject:$request->subject_id".$file->getClientOriginalName();
                    // if($user->profile_picture != $file_name){
                    //     unlink(getcwd().$user->profile_picture);
                    // }
                    $path = "/images/profile-pictures/$file_name";
                    $file->move('question-file/', $file_name);
                    $data->files = $path;
                }
                $data->file_type = $file->getClientOriginalExtension();
                $data->opsi_a = $request->opsi_a;
                $data->opsi_b = $request->opsi_b;
                $data->opsi_c = $request->opsi_c;
                $data->opsi_d = $request->opsi_d;
                $data->opsi_de = $request->opsi_de;
                $data->update();
                return $this->onSuccess($data, 201);
            }
        }
        return $this->onError(403, 'Access forbidden');
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
