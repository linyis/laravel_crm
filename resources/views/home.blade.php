@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 p-2">
            <form action="{{route('home')}}" method="GET">
                <input name="search" placeholder="Search word" />
                <input type="submit" value="開始搜尋">
            </form>
        </div>

    </div>
    <div class="row">
        {{ $crms->links() }}
        <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">發文人</th>
                <th scope="col">主題</th>
                <th scope="col">發文日期</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
            @foreach ($crms as $crm)
              <tr>
                <th scope="row">{{((request('page')>0 ? request('page') : 1)-1)*15+$loop->index+1}}</th>
                <td>{{$crm->user->name}}</td>
              <td><a href="/home/{{$crm->id}}/detail">{{$crm->subject}}</a></td>
                <td>{{$crm->created_at}}</td>
                <td>@mdo</td>
              </tr>
            @endforeach
            </tbody>
        </table>
        {{ $crms->links() }}
    </div>
</div>
@endsection
