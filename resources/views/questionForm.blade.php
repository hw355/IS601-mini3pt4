@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Question</div>
                    <div class="card-body">
                        @if($edit === FALSE)
                            {!! Form::model($question, ['action' => 'QuestionController@store', 'enctype' => 'multipart/form-data']) !!}
                            @csrf
                            <div class="form-group">
                                {!! Form::label('body', 'Body') !!}
                                {!! Form::text('body', $question->body, ['class' => 'form-control','required' => 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::open(['url' => '/uploads/images/', 'role'=>'form', 'files' => true]) !!}
                                {!! Form::label('image', 'Image') !!}
                                {!! Form::file('image', $question->image) !!}
                            </div>
                            <button class="btn btn-success float-right" value="submit" type="submit" id="submit">Save
                            </button>
                            {!! Form::close() !!}

                        @else()
                            {!! Form::model($question, ['route' => ['questions.update', $question->id], 'method' => 'put']) !!}
                            <div class="form-group">
                                {!! Form::label('body', 'Body') !!}
                                {!! Form::text('body', $question->body, ['class' => 'form-control','required' => 'required']) !!}
                            </div>
                            @if ($question->image)
                                <img src="{{asset("storage/$question->image")}}" alt="{{$question->image}}" class="img-fluid" alt="Responsive image">
                            @endif
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection