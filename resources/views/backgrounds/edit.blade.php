@extends("layouts/app")

@section("content")

<div class="row mt-7">

    <div class="col-12">
        @if(!Auth::guest() && Auth::user()->role == "admin")
            {!! Form::open(["action" => ["BackgroundsController@destroy", $background->id], "method" => "POST"]) !!}
                {{Form::hidden("_method", "DELETE")}}
                {{Form::submit("Delete", ["class" => "artshop-button red"])}}
            {!! Form::close() !!}
        @endif
    </div>

    <div class="col-12 text-center mb-5">
        <h2 class="text-uppercase">Edit background:</h2>
    </div>

    <div class="col-12 mb-5">
        <img src="/storage/backgrounds/{{$background->background_name}}" class="p-0 m-0 img-fluid"/>
    </div>

    <div class="col-12 col-xl-6 mb-5">
        {!! Form::open(["action" => ["BackgroundsController@update", $background->id], "method" => "POST", "files" => true]) !!}
        <div class="form-group">
            {{Form::label("title", "Title:")}}
            {{Form::text("title", $background->title, ["class" => "form-control", "placeholder" => ""])}}
        </div>
        <div class="form-group">
            {{Form::label("width", "Width (cm):")}}
            {{Form::text("width", "$background->width", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("height", "Height (cm):")}}
            {{Form::text("height", "$background->height", ["class" => "form-control"])}}
        </div>
        <div class="form-group">
            {{Form::label("picture", "Picture:")}}
            {{Form::file("picture", ["class" => "form-control"])}}
        </div>
        {{Form::hidden("_method", "PUT")}}
        {{Form::submit("Submit", ["class" => "artshop-button mt-5"])}}
        {!! Form::close() !!}
    </div>

</div>

@endsection