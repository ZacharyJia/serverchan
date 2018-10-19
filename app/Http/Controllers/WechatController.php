<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Log;

class WechatController extends Controller
{

    public function serve()
    {
        $app = app('wechat.official_account');
        $app->server->push(function ($message) {
            Log::debug($message);
            switch ($message['MsgType']) {
                case 'event':
                    if (array_has($message, 'EventKey') && strpos($message['EventKey'], 'qrscene') != false) {
                        $arr = explode('_', $message['EventKey']);
                        $user = User::find($arr[1]);
                        if ($user != null) {
                            $user['openid'] = $message['FromUserName'];
                            $user->save();
                            return '绑定成功';
                        }
                    }
                    return '欢迎进入';
                    break;
                default:
                    return '好哒，我知道啦！';
                    break;
            }
        });
    }

}
