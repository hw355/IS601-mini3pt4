<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;
use Illuminate\Support\Facades\Auth;
use App\Events\AnswerAction;

class QuestionController extends Controller
{
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
    public function create()
    {
        $question = new Question;
        $edit = FALSE;

        return view('questionForm', ['question' => $question,'edit' => $edit  ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'body' => 'required|min:5',
        ], [
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
        ]);
        $input = request()->all();
        $question = new Question($input);
        $question->user()->associate(Auth::user());
        $question->save();

        return redirect()->route('home')->with('message', 'A question has been created successfully!!');
    }


        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $answers = $question->answers()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('question', ['answers' => $answers, 'question' => $question]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $edit = TRUE;

        return view('questionForm', ['question' => $question, 'edit' => $edit ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $input = $request->validate([
            'body' => 'required|min:5',
        ], [
            'body.required' => 'Body is required',
            'body.min' => 'Body must be at least 5 characters',
        ]);

        $question->body = $request->body;
        $question->save();

        return redirect()->route('questions.show',['question_id' => $question->id])->with('message', 'Your question has been updated successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('home')->with('message', 'Your question has been deleted successfully!');
    }

    public function actOnAnswer(Request $request, $id)
    {
        $action = $request->get('action');
        switch ($action) {
            case 'Like':
                Answer::where('id', $id)->increment('likes_count');
                break;

            case 'Unlike':
                Answer::where('id', $id)->decrement('likes_count');
                break;
        }
        $this->dontBroadcastToCurrentUser();

        //broadcast(new AnswerAction($id, $action))->toOthers();

        return '';
    }

}
