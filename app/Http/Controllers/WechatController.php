<?php

namespace App\Http\Controllers;

use App\Msg;
use App\User;
use Illuminate\Http\Request;

use Log;
use Hashids;

class WechatController extends Controller
{

    public function serve()
    {
        $app = app('wechat.official_account');
        Log::debug('new message arrived');
        $app->server->push(function ($message) {
            Log::debug($message);
            switch ($message['MsgType']) {
                case 'event':
                    switch ($message['Event']) {
                        case 'SCAN':
                            $user = User::find($message['EventKey']);
                            if ($user != null) {
                                $user['openid'] = $message['FromUserName'];
                                $user->save();
                                return '绑定成功';
                            }
                            break;
                        case 'subscribe':
                            $arr = explode('_', $message['EventKey']);
                            $user = User::find($arr[1]);
                            if ($user != null) {
                                $user['openid'] = $message['FromUserName'];
                                $user->save();
                                return '绑定成功';
                            }
                            break;
                    }
                    return '欢迎进入';
                    break;
                default:
                    return '好哒，我知道啦！';
                    break;
            }
        });

        return $app->server->serve();
    }


    public function detail($id)
    {
        $id = Hashids::decode($id);
        if ($id == null) {
            return 'Sorry, the page you are looking for could not be found.';
        }
        $msg = Msg::where('id', $id)
            ->first();
        if ($msg != null) {
            return view('detail', ['msg' => $msg]);
        } else {
            return 'Sorry, the page you are looking for could not be found.';
        }
    }

}
