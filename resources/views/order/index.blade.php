@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        @include('layouts.menu')

        <div class="col-md-11">

            <div>
                <a href="{{route("order.create")}}" class="btn btn-success btn-sm">Create New</a>
            </div>
            @if (\Session::has('message'))
            <div class="alert alert-success">
            <p>{{ \Session::get('message') }}</p>
            </div><br />
            @endif
            <div class="alert-custom"></div>
            <div class="row">

            </div>
        </div>
    </div>
</div>
@endsection
