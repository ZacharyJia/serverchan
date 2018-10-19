@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-primary" role="alert">
                                {{ session('status') }}
                            </div>
                        @else
                        你的SCKEY为：
                        <div class="alert alert-primary" role="alert">
                            <pre>{{ $sckey }}</pre>
                        </div>
                        @endif

                        <a class="btn btn-danger" data-toggle="tooltip" data-placement="top"
                           title="重新生成SCKEY会造成之前的所有SCKEY不可用" href="/sckey/gen">重新生成SCKEY</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
