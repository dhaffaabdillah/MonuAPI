<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamPackage;
use App\Models\Question;
use App\Models\TakeExam;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
    public function index(Request $request): JsonResponse
    {
        if (Auth::check()) {
            $data = Answer::paginate($request->get('limit'));
            return $this->onSuccessJson($data, "Answer fetched.", 200);
            // dd($data);
        }
        return $this->onError(401, 'Login terlebih dahulu!');
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
    public function saveAnswer(Request $request, $exam_id)
    {
        if (Auth::check()) {
            $getQuestion = ExamPackage::select('question_id')
                            // ->leftJoin('questions_cognitive', 'question_id', '=', 'questions_cognitive.id')
                            ->where([['exam_id', '=', $exam_id]])->orderBy('question_id','ASC')->get();
            $data = Answer::create([
                'exam_id' => $exam_id,
                'user_id' => Auth::user()->id,
                'question_list_id' => json_encode($getQuestion),
                'answer_list' => json_encode($request->answer_list),
                'start_at' => Carbon::now(),
                'scores' => mt_rand(65*10, 95*10) / 10
            ]);

            return $this->onSuccess($data, 'Data submitted', 201);
        }
    }

    public function saveAnswer2(Request $request, $exam_id)
    {

        // dd(request()->get('question_list_id'));
        if (Auth::check()) {
            $validator = Validator::make($request->all(), $this->answerQuestionValidatedRules());
            if (!$validator->fails()) {

                $dataQ = [];
                $getQuestion = ExamPackage::select('question_id', 'correct_answer')
                            ->leftJoin('questions_cognitive', 'question_id', '=', 'questions_cognitive.id')
                            ->where([['exam_id', '=', $exam_id]])->orderBy('question','ASC')->get();
                // $getCorrectAnswer = ExamPackage::select('correct_answer')->leftJoin('questions_cognitive', 'question_id', '=', 'id')->order->get();
                $encodedAnswer = json_encode($request->question_list_id);
                $decodedAnswer = json_decode(json_encode($request->question_list_id), true);
                $a = (array) $encodedAnswer[0];
                $answer = new Answer;
                $answer->exam_id = $exam_id;
                $answer->user_id = Auth::user()->id;
                $answer->question_list_id = $request->question_list_id[0]['answer'];
                $answer->question_list_id = $decodedAnswer[0]['answer'];
                $answer->start_at = Carbon::now();
                $answer->scores = 
                $answer->save();

                // foreach($request->question_list_id as $q_i){
                //     // DB::query("INSERT INTO list_question_coba VALUES (NULL, ".$exam_id.", ".$q_i->question_id.", ".$q_i->answer.")");
                //     // dd($q_i['answer'], "kdwkdkwendkwe");
                //     $answer = new Answer;
                //     $answer->exam_id = $exam_id;
                //     $answer->user_id = Auth::user()->id;
                //     $answer->question_list_id = $q_i[0];
                //     $answer->start_at = Carbon::now();
                //     $answer->save();
                // }
                // dd($answer);
                // dd($answer);
                // $answer = Answer::create([
                //     'exam_id' => $request->exam_id,
                //     'user_id' => Auth::user()->id,
                //     'question_list_id' => $getQuestion,
                //     'start_at' => Carbon::now(),
                    
                // ]);

                
                // foreach ($getQuestion as $key => $value) {
                    
                // }
                // return $this->onSuccess($answer, 'Data berhasil disubmit', 201);
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized'); 
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
