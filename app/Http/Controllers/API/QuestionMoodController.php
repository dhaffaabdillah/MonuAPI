<?php

namespace App\Http\Controllers\API;

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
        $question = QuestionMood::all();
        return $this->onSuccess([$question], 200);
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
        $data = QuestionMood::create([
            'question' => $request->question,
            'option_a' => "😁",
            'option_b' => "😑",
            'option_c' => "😥",
        ]);

        return $this->onSuccess($data, "Data Submitted", 201);
    }

    /**k
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = QuestionMood::find($id);
        return $this->onSuccess($data, 'Data fetched', 200);
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
        $data = QuestionMood::findOrFail($id);
        $data->question = $request->question;
        $data->update();
        return $this->onSuccess($data, "Data Updated", 200);
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
            $QuestionMood = QuestionMood::find($id); // Find the id of the QuestionMood passed
            $QuestionMood->delete(); // Delete the specific QuestionMood data
            if (!empty($QuestionMood)) {
                return $this->onSuccess([$QuestionMood], 'User Deleted');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
