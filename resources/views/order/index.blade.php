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
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">訂單編號</th>
                        <th scope="col">總價</th>
                        <th scope="col">發文日期</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <th scope="row">{{((request('page')>0 ? request('page') : 1)-1)*10+$loop->index+1}}</th>
                        <td>{{$order->order_no}}</td>

                    <td>{{$order->payment}}</td>
                        <td>{{$order->created_at}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
