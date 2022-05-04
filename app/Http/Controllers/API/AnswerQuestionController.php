<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\Question;
use App\Models\TakeExam;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnswerQuestionController extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function saveAnswer(Request $request, $exam_id, $user_id)
    {
        if (Auth::check() && Auth::user()->role == 1) {
            $validator = Validator::make($request->all(), $this->answerQuestionValidatedRules());
            if (!$validator->fails()) {
                $getQuestion = Question::where([['exam_id', '=', $exam_id]])->get();
                foreach ($getQuestion as $key => $value) {
                    $data = Question::create([
                        'exam_id' => $exam_id
                    ]);
                }
            }
        }
    }

    public function storeTemporary(Request $request)
    {
        $id_user = Auth::user()->id;
        if (Auth::check() && Auth::user()->roles == 1) {
            if ($request->tokens == Exam::select('tokens')) {
                $update_ = "";
                $queue = json_decode(file_get_contents('php://input'));
                for ($a=0; $a < $queue->totalQuestion; $a++) { 
                    $_tanswer 	    = "option_".$a;
                    $_t_question_id = "question_id_".$a;
                    $_doubt 		= "rg_".$a;
                    $answer_ 	    = empty($queue->$_tanswer) ? "" : $queue->$_tanswer;
                    $update_	   .= "".$queue->$_t_question_id.":".$answer_.":".$queue->$_doubt.",";
                }
                $update_ = substr($update_, 0, -1);
                DB::raw("UPDATE take_exams SET answer_list = '".$update_."' WHERE test_id = '$request->test_id' AND user_id = '".Auth::user()->id."'");

                $questionReturn = TakeExam::select('answer_list')->where('test_id', $request->test_id)->where('user_id', Auth::user()->id);
                $dataReturn = $questionReturn->row_array();
                $return = explode(":", $dataReturn['answer_list']);
                $results = array();
                foreach($return as $key => $value){
                    $pc_return  = explode(":", $value);
				    $idx 		= $pc_return[0];
				    $val 		= $pc_return[1].'_'.$pc_return[2];
				    $results[]   = $val;
                }
                return $this->onSuccess($results, 'Berhasil disimpan sementara', 200);
            }
            return $this->onError(400, 'Token yang kamu masukkan salah!');
        }
        return $this->onError(403, 'Anda tidak berhak masuk ke fitur ini');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }
}
