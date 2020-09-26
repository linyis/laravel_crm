@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        @include('layouts.menu')

        <div class="col-md-11">

            <div class="row">
                <div class="col-md-12 col-sm-12 p-2">
                    <form name="search" action="{{route('crm.index')}}" method="GET">
                        <input name="search" placeholder="Search word" />
                        <input type="submit" value="開始搜尋">
                    </form>
                </div>

            </div>

            <div>
                <a href="{{route("crm.create")}}" class="btn btn-success btn-sm">Create New</a>
            </div>
            @if (\Session::has('message'))
            <div class="alert alert-success">
            <p>{{ \Session::get('message') }}</p>
            </div><br />
            @endif
            <div class="alert-custom"></div>
            <div class="row">
                {{ $crms->links() }}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">發文人</th>
                        <th scope="col">主題</th>
                        <th scope="col">發文日期</th>
                        <th scope="col">瀏覽次數</th>
                        <th scope="col">#</th>
                        <th scope="col">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($crms as $crm)
                    <tr>
                        <th scope="row">{{((request('page')>0 ? request('page') : 1)-1)*10+$loop->index+1}}</th>
                        <td>{{$crm->user->name}}</td>

                    <td><a href="{{route('crm.show',$crm->id)}}">{{$crm->subject}}</a></td>
                        <td>{{$crm->created_at}}</td>
                        <td>{{$crm->count}}</td>
                    <td><a href="{{route('crm.edit', $crm->id)}}" class="btn btn-warning"><i class="fa fa-pencil"></i>Edit</a></td>
                    <td>
                        <form class="deletepost" action="{{route('crm.destroy', $crm->id)}}" method="post" onsubmit="return confirm('Are you sure that you want to delete this post?');">
                            @csrf
                            @method('delete')
                                <button class="btn btn-danger">
                                    <i class="fa fa-trash-o"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $crms->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
