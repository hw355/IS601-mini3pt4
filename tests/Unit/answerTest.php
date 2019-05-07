<?php

namespace Tests\Unit;

use App\Answer;
use App\Events\AnswerAction;
use App\Question;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class answerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSave()
    {
        $user = factory(\App\User::class)->make();
        $user->save();
        $question = factory(\App\Question::class)->make();
        $question->user()->associate($user);
        $question->save();
        $answer = factory(\App\Answer::class)->make();
        $answer->user()->associate($user);
        $answer->question()->associate($question);
        $this->assertTrue($answer->save());
    }

    public function testActOnAnswer()
    {
        $user = factory(\App\User::class)->make();
        $user->save();
        $question = factory(\App\Question::class)->make();
        $question->user()->associate($user);
        $question->save();
        $answer = factory(\App\Answer::class)->make();
        $answer->user()->associate($user);
        $answer->question()->associate($question);

        $action = 'Like';

        if ($action == 'Like') {
            $answer->likes_count ++;
        }

        $this->assertTrue($answer->save());
    }

    public function testLikeCountProperty()
    {
        $answer = Answer::find(5);
        $this->assertIsInt($answer->likes_count);
    }

}
