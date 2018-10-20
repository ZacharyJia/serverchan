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
                        {{--<div class="alert alert-primary" role="alert">--}}
                            <pre>{{ $sckey }}</pre>
                        {{--</div>--}}
                        @endif

                        <a class="btn btn-danger" data-toggle="tooltip" data-placement="top"
                           title="重新生成SCKEY会造成之前的所有SCKEY不可用" href="/sckey/gen">重新生成SCKEY</a>
                        <p>发送消息非常简单，只需要向以下URL发一个GET或者POST请求：</p>
                        <pre>{{ \Illuminate\Support\Facades\Request::root() }}/send/{{ $sckey }}</pre>
                            <br/>
                        <p>
                            接受三个参数：
                            <ul>
                                <li>channel: 推送渠道，填写 <code>wechat</code>或<code>work</code>，分别表示微信公众号渠道和企业微信渠道。可空。值为空时，会优先选用微信公众号渠道，如果只绑定了企业微信则会自动选择企业微信。</li>
                                <li>title: 消息标题，最长为256，必填。</li>
                                <li>content: 消息内容，最长64Kb，可空。</li>
                            </ul>

                            <br />

                            最简单的消息发送方式是通过浏览器，在地址栏输入以下URL，回车后即可发送：
                        </p>
                        <pre>{{ \Illuminate\Support\Facades\Request::root() }}/send/{{ $sckey }}?title=主人服务器又挂掉啦~</pre>

                        <p>
                            在PHP中，可以直接用file_get_contents来调用：
                        </p>
                        <pre>
file_get_contents('{{ \Illuminate\Support\Facades\Request::root() }}/send/{{ $sckey }}?text='.urlencode('主人服务器又挂掉啦~'));</pre>
                        <p>
                            可以把它封装成一个函数：
                        </p>
                        <pre>
function sc_send($title, $content = '' , $key = '{{ $sckey }}'  )
{
    $postdata = http_build_query(
        array(
            'title' => $text,
            'content' => $content,
            'channel' => 'wechat'
        )
    );

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);
    return $result = file_get_contents('{{ \Illuminate\Support\Facades\Request::root() }}/send/'.$key, false, $context);
}
                        </pre>
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
