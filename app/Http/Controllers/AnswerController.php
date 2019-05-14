<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Question;
use Illuminate\Support\Facades\Auth;
use App\Traits\UploadTrait;

class AnswerController extends Controller
{

    use UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($question)
    {
        $answer = new Answer;
        $edit = FALSE;

        return view('answerForm', ['answer' => $answer,'edit' => $edit, 'question' =>$question]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $question)
    {
        $request->validate([
            'body' => 'required|min:5',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
            'image.image' => 'The image must be an image.',
            'image.mimes' => 'file type must be jpeg, png, jpg, or gif',
            'image.max' => 'file size  must be less than  2 MB',
        ]);
        $input = request()->all();
        $answer = new Answer($input);
        $question = Question::find($question);
        $answer->user()->associate(Auth::user());
        $answer->question()->associate($question);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $name = $answer->user_id . '_' . $answer->question_id . '_' . $answer->id . '_' . time();
            $folder = '/uploads/answers/';
            $filePath = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 's3', $name);
            $answer->image = $filePath;
        }
            $answer->save();

        return redirect()->route('questions.show',['question_id' => $question->id])->with('message', 'Your answer has been submitted successfully!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($question,  $answer)
    {
        $answer = Answer::find($answer);
        return view('answer')->with(['answer' => $answer, 'question' => $question]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($question,  $answer)
    {
        $answer = Answer::find($answer);
        $edit = TRUE;

        return view('answerForm', ['answer' => $answer, 'edit' => $edit, 'question'=>$question ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $question, $answer)
    {
        $input = $request->validate([
            'body' => 'required|min:5',
        ], [
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
        ]);

        $answer = Answer::find($answer);
        $answer->body = $request->body;
        $answer->save();

        return redirect()->route('answers.show',['question_id' => $question, 'answer_id' => $answer])->with('message', 'Your answer has been updated successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($question, $answer)
    {
        $answer = Answer::find($answer);
        $answer->delete();

        return redirect()->route('questions.show',['question_id' => $question])->with('message',  'Your answer has been deleted successfully!!');
    }
}
