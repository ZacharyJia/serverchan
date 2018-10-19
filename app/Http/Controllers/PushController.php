<?php

namespace App\Http\Controllers;

use App\Msg;
use App\User;
use Illuminate\Http\Request;

class PushController extends Controller
{
    public function push(Request $request, $sckey)
    {
        $user = User::where('sckey', $sckey)->first();
        if ($user != null) {
            $openid = $user['openid'];
            if ($openid == null) {
                return ['res' => 'failed', 'msg' => 'you have not bound wechat account'];
            }

            $title = $request->input('title');
            $content = $request->input('content');
            if ($title == null) {
                return ['res' => 'failed', 'msg' => 'title cannot be null'];
            }
            if ($content == null) {
                $content = '';
            }

            $msg = new Msg();
            $msg['user_id'] = $user['id'];
            $msg['title'] = $title;
            $msg['content'] = $content;
            $msg->save();

            $app = app('wechat.official_account');
            $app->template_message->send([
                'touser' => $openid,
                'template_id' => 'jBj-0KtSWQYBef5FEVn78Wudd8ozd67cH2t5quT5t6k',
                'url' => 'http://wechat.zacharyjia.me/detail/' . $msg['id'],
                'data' => [
                    'title' => $title,
                    'content' => $content,
                ],
            ]);

            return ['res' => 'success', 'msg' => 'success'];
        } else {
            return ['res' => 'failed', 'msg' => 'sckey error!'];
        }
    }
}
