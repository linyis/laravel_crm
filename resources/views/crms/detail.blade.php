@extends('layouts.app')

@section('content')

<div class="container">
    <div class="float-right">
    <a class="btn btn-primary btn-lg" href="{{route('home')}}" role="button">Back</a>
    </div>
    <div class="jumbotron">
    <p class="lead">{{$crm->subject}}<span class="px-12 bg-red-300">作者:{{$crm->user->name}}</span></p>
        <hr class="my-4">
        <p>{{$crm->content}}</p>
        <br>
        @if (Auth::check())
            @if (Auth::user()->id==$crm->user_id)
        <a class="btn btn-primary btn-lg" href="#" role="button">Edit</a>
        <a class="btn btn-primary btn-lg" href="#" role="button">Delete</a>
            @endif
        @endif
    </div>


</div>
@endsection
