@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">历史消息</div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>标题</th>
                                <th>内容</th>
                                <th>时间</th>
                            </tr>
                            @foreach($msgs as $msg)
                                <tr>
                                    <td>{{ \Vinkla\Hashids\Facades\Hashids::encode($msg['id']) }}</td>
                                    <td>{{ $msg['title'] }}</td>
                                    <td>{{ $msg['content'] }}</td>
                                    <td>{{ $msg['created_at'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                        {{ $msgs->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
