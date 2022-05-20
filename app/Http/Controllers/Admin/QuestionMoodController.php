<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionMood;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class QuestionMoodController extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $question = Question::where([['question_type', '=', 'mood']])->get();
        // return $this->onSuccess([$question], 200);
        return view('admin.questions-mood.index', compact('question'));
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
        $data = Question::create([
            'question' => $request->question,
            'question_type' => "mood",
            'option_a' => "https://cdn.pixabay.com/photo/2020/12/27/20/24/smile-5865208_640.png",
            'option_b' => "https://freepngimg.com/download/icon/1000092-expressionless-face-emoji-free-icon.png",
            'option_c' => "https://www.clipartmax.com/png/middle/64-644516_very-sad-emoji-sad-emoji.png",
        ]);

        return redirect()->route('qm-index');
        // return $this->onSuccess($data, "Data Submitted", 201);
    }

    /**k
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Question::where([['question_type', '=', 'mood']])->findOrFail($id);
        // return $this->onSuccess($data, 'Data fetched', 200);
        return view('admin.questions-mood.edit', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Question::where([['question_type', '=', 'mood']])->findOrFail($id);
        // return $this->onSuccess($data, 'Data fetched', 200);
        return view('admin.questions-mood.edit', compact('data'));
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
        $data = Question::where([['question_type', '=', 'mood']])->findOrFail($id);
        $data->question = $request->question;
        $data->option_a = "https://cdn.pixabay.com/photo/2020/12/27/20/24/smile-5865208_640.png";
        $data->option_b = "https://freepngimg.com/download/icon/1000092-expressionless-face-emoji-free-icon.png";
        $data->option_c = "https://www.clipartmax.com/png/middle/64-644516_very-sad-emoji-sad-emoji.png";
        $data->update();
        // return $this->onSuccess($data, "Data Updated", 200);
        return redirect()->route('qm-index');
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
            $QuestionMood = Question::where([['question_type', '=', 'mood']])->find($id); // Find the id of the QuestionMood passed
            $QuestionMood->delete(); // Delete the specific QuestionMood data
            if (!empty($QuestionMood)) {
                // return $this->onSuccess([$QuestionMood], 'User Deleted');
                return redirect()->back();
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
