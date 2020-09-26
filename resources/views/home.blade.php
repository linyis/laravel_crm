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
                <th scope="col">類別</th>
                <th scope="col">主題</th>
                <th scope="col">發文日期</th>
                <th scope="col">瀏覽次數</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($crms as $crm)
              <tr>
                <th scope="row">{{((request('page')>0 ? request('page') : 1)-1)*10+$loop->index+1}}</th>
                <td>{{$crm->user->name}}</td>
                <td>
                    @foreach ($crm->categories as $category)
                    < {{renderParent($category)}} >
                    @endforeach
                </td>
              <td><a href="{{route('home.detail',$crm->id)}}">{{$crm->subject}}</a></td>
                <td>{{$crm->created_at}}</td>
                <td>{{$crm->count}}</td>
              </tr>
            @endforeach
            </tbody>
        </table>
        {{ $crms->links() }}
    </div>
</div>
@endsection

