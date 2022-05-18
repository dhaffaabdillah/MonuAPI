<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\TakeQuestionMood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TakeQuestionMoodContoller extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('take_question_mood')->leftJoin('users', 'users.id', '=', 'take_question_mood.user_id')
                // ->leftJoin('exams', 'exams.id', '=', 'take_question_mood.exam_id')->get();
                ->leftJoin('take_exam.id', '=', 'take_question_mood.take_exam_id')->get();
        if ($data->scores <= 70 && $data->answer_option == "😥") {
            $msg = "Butuh pendampingan dan bimbingan ekstra";
        } else if($data->scores > 70 && $data->scores < 80 && $data->answer_option == "😑" || $data->answer_option == "😥") {
            $msg = "Butuh pendampingan dan bimbingan";
        } else {
            $msg = "Ananda tidak perlu pendampingan dan bimbingan";
        }
        return $this->onSuccess($data, $msg , 200);
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
    public function store(Request $request, $exam_id)
    {
        $data = TakeQuestionMood::create([
            'user_id' => Auth::user()->id,
            'exam_id' => $exam_id,
            'answer_option' => $request->answer_option,
            'answer_detail' => $request->answer_detail,
        ]);

        return $this->onSuccess($data, 'Data submitted', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TakeQuestionMood  $takeQuestionMood
     * @return \Illuminate\Http\Response
     */
    public function show(TakeQuestionMood $takeQuestionMood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TakeQuestionMood  $takeQuestionMood
     * @return \Illuminate\Http\Response
     */
    public function edit(TakeQuestionMood $takeQuestionMood)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TakeQuestionMood  $takeQuestionMood
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TakeQuestionMood $takeQuestionMood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TakeQuestionMood  $takeQuestionMood
     * @return \Illuminate\Http\Response
     */
    public function destroy(TakeQuestionMood $takeQuestionMood)
    {
        //
    }
}
