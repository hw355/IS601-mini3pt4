@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Answer</div>
                    <div class="card-body">

                        @if($edit === FALSE)
                            {!! Form::model($answer, ['route' => ['answers.store', $question], 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                            @csrf

                            <div class="form-group">
                                {!! Form::label('body', 'Body') !!}
                                {!! Form::text('body', $answer->body, ['class' => 'form-control','required' => 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::open(['url' => '/uploads/images/', 'role'=>'form', 'files' => true]) !!}
                                {!! Form::label('image', 'Image') !!}
                                {!! Form::file('image', $answer->image) !!}
                            </div>

                            <button class="btn btn-success float-right" value="submit" type="submit" id="submit">Save
                            </button>
                            {!! Form::close() !!}

                        @else()
                            {!! Form::model($answer, ['route' => ['answers.update', $question, $answer], 'method' => 'patch', 'enctype' => 'multipart/form-data']) !!}
                            @csrf

                            <div class="form-group">
                                {!! Form::label('body', 'Body') !!}
                                {!! Form::text('body', $answer->body, ['class' => 'form-control','required' => 'required']) !!}
                            </div>
                            @if ($answer->image)
                                <img src="{{asset("storage/$answer->image")}}" alt="{{$answer->image}}" class="img-fluid" alt="Responsive image">
                            @endif

                            <button class="btn btn-success float-right" value="submit" type="submit" id="submit">Save
                            </button>
                            {!! Form::close() !!}
                        @endif


                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
