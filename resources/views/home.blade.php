@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 p-2">
            <input name="search" placeholder="Search word" />
        </div>
        <div class="col-md-12">
            <form action= {{route('home')}}
        </div>
    </div>
    <div class="row">
        @foreach ($users as $user)
        <div class="col-md-3 p-2">

            {{$user->name}}




        </div>
        @endforeach
    </div>
</div>
@endsection
