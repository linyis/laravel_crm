@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
    <a class="btn btn-primary btn-lg" href="{{route($back)}}" role="button">Back</a>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form method="POST" action="{{ route('crm.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group row">
                    <label for="subject" class="col-sm-4 col-form-label text-md-right">主題</label>

                    <div class="col-md-6">
                        <input id="subject" type="subject" class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" name="subject" value="{{ old('subject') }}" required autofocus>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="content" class="col-md-4 col-form-label text-md-right">內容</label>

                    <div class="col-md-6">
                        <textarea id="content" type="password" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" required>
                        </textarea>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="content" class="col-md-4 col-form-label text-md-right">上傳圖片</label>

                    <div class="col-md-6">
                        <input id="subject" type="file" name="image">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            完成
                        </button>

                    </div>
                </div>
            </form>



        </div>
    </div>

</div>
@endsection
