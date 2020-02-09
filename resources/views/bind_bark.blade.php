@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">绑定Bark App</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-warning" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p> Bark 是一个 iOS App，它可以帮助你推送自定义通知到你的 iPhone。详情查看：
                            <a href="https://github.com/Finb/Bark" target="_blank">Bark</a>
                        </p>
                        <form method="post">
                            <div class="form-group">
                                <label for="url">Bark推送URL</label>
                                <input type="text" class="form-control" id="url" name="url" value="{{ $bark_url }}">
                                {{ csrf_field() }}
                            </div>
                            <button type="submit" class="btn btn-primary">保存</button>
                            <a href="{{$push_url}}" target="_blank" class="btn btn-success">Bark推送测试</a>
                        </form>
                        <form method="post" action="unbind_work">
                            <div class="form-group">
                                {{ csrf_field() }}
                            </div>
                            <button type="submit" class="btn btn-danger">解除Bark绑定</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
