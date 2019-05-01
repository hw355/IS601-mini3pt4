@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Question</div>

                    <div class="card-body">

                        {{$question->body}}
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-primary float-right"
                           href="{{ route('questions.edit',['id'=> $question->id])}}">
                            Edit
                        </a>
                        {{ Form::open(['method'  => 'DELETE', 'route' => ['questions.destroy', $question->id]])}}
                        <button class="btn btn-danger float-right mr-2" value="submit" type="submit" id="submit">Delete
                        </button>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><a class="btn btn-primary float-left"
                                                href="{{ route('answers.create', ['question_id'=> $question->id])}}">
                            Reply
                        </a></div>

                    <div class="card-body">
                        @forelse($answers as $answer)
                            <div class="card">
                                <div>By
                                    <b>{{ $answer->user->profile->fname}} {{$answer->user->profile->lname}}</b>
                                    on
                                    <small>{{ $answer->created_at }}</small>
                                </div>
                                <div class="card-body">{{$answer->body}}</div>
                                <div class="card-footer">

                                    <a class="btn btn-warning float-left">
                                        <button type="button" id="like-btn-{{ $answer->id }}" onclick="actOnAnswer(event);" style="background-color:transparent;border:none;" data-answer-id="{{ $answer->id }}">Like</button>
                                        <span id="likes-count-{{ $answer->id }}">{{ $answer->likes_count }}</span>
                                    </a>

                                    <a class="btn btn-primary float-right"
                                       href="{{ route('answers.show', ['question_id'=> $question->id,'answer_id' => $answer->id]) }}">
                                        View
                                    </a>

                                </div>
                            </div>
                        @empty
                            <div class="card">
                                <div class="card-body"> No Answers</div>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var updateAnswerStats = {
            Like: function (answerId) {
                document.querySelector('#likes-count-' + answerId).textContent++;
            },

            Unlike: function(answerId) {
                document.querySelector('#likes-count-' + answerId).textContent--;
            }
        };

        var toggleButtonText = {
            Like: function(button) {
                button.textContent = "Unlike";
            },

            Unlike: function(button) {
                button.textContent = "Like";
            }
        };

        var actOnAnswer = function (event) {
            var answerId = event.target.dataset.answerId;
            var action = event.target.textContent;
            toggleButtonText[action](event.target);
            updateAnswerStats[action](answerId);
            axios.post('/answer/' + answerId + '/act',
                { action: action });
        };

        Echo.channel('answer-events')
            .listen('AnswerAction', function (event) {
                console.log(event);
                var action = event.action;
                updateAnswerStats[action](event.answerId);
            })

    </script>

@endsection