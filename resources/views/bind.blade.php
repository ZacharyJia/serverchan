@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">扫码绑定</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-warning" role="alert">
                                {{ session('status') }}
                            </div>
                        @else
                            <img src="{{ $qr_url }}">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
