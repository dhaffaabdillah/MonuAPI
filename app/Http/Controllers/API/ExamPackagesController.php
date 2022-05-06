<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\apiHelper;
use App\Models\ExamPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ExamPackagesController extends Controller
{
    use apiHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $data = ExamPackage::with(['questions', 'exams'])->paginate($request->limit);
            return $this->onSuccess([$data], 'Exam Package fetched', 200);
        }
        return $this->onError(401, 'Unauthorized');
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
        if (in_array(Auth::user()->role, [2, 3])) {
            $validate = Validator::make($request->all(), $this->examPackagesValidatedRules());
            if (!$validate->fails()) {
                $data = ExamPackage::create([
                    'exam_id' => $request->exam_id,
                    'question_id' => $request->question_id,
                ]);
                return $this->onSuccess([$data], 'Exam Package created successfully!', 201);
            }
            return $this->onError(400, $validate->errors()->all());
        }
        return $this->onError(403, 'Your role doesnt support');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamPackage  $examPackage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::check()){
            $data = ExamPackage::findOrFail($id)->with(['exams', 'questions']);
            if (!$data) {
                return $this->onError(404,'Data not found!');
            }
            return $this->onSuccess([$data], 'Exam Package fetched', 200);
        }
         return $this->onError(401, 'Unauthorized');
    }

    public function fetchExam($exam_id)
    {
        if(Auth::check()){
            $data = ExamPackage::with(['questions'])->where([['exam_id', '=', $exam_id]])->get();
            if (!$data) {
                return $this->onError(404,'Data not found!');
            }
            return $this->onSuccess([$data], 'Exam Package fetched', 200);
        }
         return $this->onError(401, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExamPackage  $examPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamPackage $examPackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamPackage  $examPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::check()) {
            $data = ExamPackage::findOrFail($id)->with(['exams', 'questions']);
            if (!$data) {
                return $this->onError(404,'Data not found!');
            }
            $data->exam_id = $request->exam_id;
            $data->question_id = $request->question_id;
            $data->update();
            return $this->onSuccess([$data], 'Exam Package updated successfully!', 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamPackage  $examPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role == 3) {
            $ExamPackage = ExamPackage::find($id); // Find the id of the ExamPackage passed
            $ExamPackage->delete(); // Delete the specific ExamPackage data
            if (!empty($ExamPackage)) {
                return $this->onSuccess([$ExamPackage], 'ExamPackage Deleted');
            }
            return $this->onError(404, 'ExamPackage Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    public function search(Request $request)
    {
        $exam_id = $request->get('exam_id');
        if (Auth::check()) {
            $examP = ExamPackage::where('exam_id', 'like', "%{$exam_id}%")
                 ->get();
            if (!$examP) {
                return $this->onError(404, 'Not found');
            }
            return $this->onSuccess([$examP], 200);
        }
        
        return $this->onError(401, 'Unauthorized');
    }
}
