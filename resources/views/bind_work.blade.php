@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">绑定企业微信</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-warning" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="post">
                            <div class="form-group">
                                <label for="work_id">企业微信ID</label>
                                <input type="text" class="form-control" id="work_id" name="work_id" value="{{ $work_id }}">
                                {{ csrf_field() }}
                            </div>
                            <button type="submit" class="btn btn-primary">验证并保存</button>
                        </form>
                        <form method="post" action="unbind_work">
                            <div class="form-group">
                                {{ csrf_field() }}
                            </div>
                            <button type="submit" class="btn btn-danger">解除企业微信绑定</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
