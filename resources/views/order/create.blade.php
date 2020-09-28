@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
    <a class="btn btn-primary btn-lg" href="{{route($back)}}" role="button">Back</a>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form method="POST" action="{{ route('order.store') }}" >
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label text-md-right">Email</label>

                    <div class="col-md-6">
                        <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" required autofocus>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile" class="col-md-4 col-form-label text-md-right">手機</label>

                    <div class="col-md-6">
                        <input id="mobile" type="text" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="" required>

                    </div>
                </div>
                <hr />
                @foreach ($goods as $good)
                <div class="form-group row">
                    <label for="mobile" class="col-md-2 col-form-label text-md-right">商品 {{$loop->index+1}}</label>

                    <div class="col-md-4 col-form-label text-md-right">
                    <input type="text" name="name[]" value="{{ $good->name }}" READONLY >
                    </div>

                    <div class="col-md-2 col-form-label text-md-right">
                        <input type="text" name="quanity[]" value="1" READONLY >
                    </div>

                    <div class="col-md-4 col-form-label text-md-right">
                        <input type="text" name="price[]" value="{{ $good->price }}" READONLY>
                    </div>
                </div>
                @endforeach

                <div class="form-group row">
                    <label for="mobile" class="col-md-4 col-form-label text-md-right">總價</label>

                    <div class="col-md-6">
                        <input id="total_price" type="text" class="form-control{{ $errors->has('total_price') ? ' is-invalid' : '' }}" name="total_price" value="{{ $goods->sum('price') }}" READONLY >

                    </div>
                </div>

                <hr />

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
