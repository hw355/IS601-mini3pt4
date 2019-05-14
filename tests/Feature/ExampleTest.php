<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Events\AnswerAction;
use Illuminate\Support\Facades\Event;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.jpg', $disk);

        return $file;
    }
}

class ExampleTest extends TestCase
{
    use UploadTrait;

    /**
     * A basic test example.
     *
     * @return void
     */

    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    function testUpload()
    {
        $user = factory(\App\User::class)->make();
        $user->save();
        $question = factory(\App\Question::class)->make();
        $question->user()->associate($user);
        $question->save();
        $answer = factory(\App\Answer::class)->make();
        $answer->user()->associate($user);
        $answer->question()->associate($question);
        $answer->save();

        $name = $answer->user_id . '_' . $answer->question_id . '_' . $answer->id . '_' . time();
        $image = UploadedFile::fake()->image($name);
        $folder = '/uploads/answers/';
        $this->uploadOne($image, $folder, 'public', $name);
        $filePath = 'storage/app/public' . $folder . $name;
        $answer->image = $filePath;

        $this->assertFileExists($filePath . '.jpg');
    }

}
