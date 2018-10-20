<?php

namespace App\Http\Controllers;

use App\Msg;
use App\User;
use Illuminate\Http\Request;
use Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as RequestFacade;

class PushController extends Controller
{
    public function push(Request $request, $sckey)
    {
        $user = User::where('sckey', $sckey)->first();
        if ($user != null) {

            $channel = $request->input('wechat');
            if (empty($channel)) {
                if ($user['work_id'] != null) {
                    $channel = 'work';
                } else {
                    $channel = 'wechat';
                }
            }

            $title = $request->input('title');
            $content = $request->input('content');
            if ($title == null) {
                return ['res' => 'failed', 'msg' => 'title cannot be null'];
            }
            if ($content == null) {
                $content = '无内容';
            }

            $msg = new Msg();
            $msg['user_id'] = $user['id'];
            $msg['title'] = $title;
            $msg['content'] = $content;
            $msg->save();

            if ($channel == 'wechat') {
                $this->send_wechat($user, $msg);
            } else {
                $this->send_work($user, $msg);
            }

            return ['res' => 'success', 'msg' => 'success'];
        } else {
            return ['res' => 'failed', 'msg' => 'sckey error!'];
        }
    }

    protected function send_wechat($user, $msg)
    {
        $openid = $user['openid'];
        if ($openid == null) {
            return ['res' => 'failed', 'msg' => 'you have not bound wechat account'];
        }

        $app = app('wechat.official_account');
        $app->template_message->send([
            'touser' => $openid,
            'template_id' => 'jBj-0KtSWQYBef5FEVn78Wudd8ozd67cH2t5quT5t6k',
            'url' => RequestFacade::root() . '/detail/' . Hashids::encode($msg['id']),
            'data' => [
                'title' => $msg['title'],
                'content' => $msg['content'],
            ],
        ]);

        return true;
    }

    protected function send_work($user, $msg)
    {
        $work_id = $user['work_id'];
        if (empty($work_id)) {
            return ['res' => 'failed', 'msg' => 'you have not bound wechat work id'];
        }
        $app = app('wechat.work');
        $res = $app->message->send([
            'touser' => $work_id,
            'msgtype' => 'textcard',
            'agentid' => env('WECHAT_WORK_AGENT_ID', '0'),
            'textcard' => [
                'title' => $msg['title'],
                'description' => $msg['content'],
                'url' => RequestFacade::root() . '/detail/' . Hashids::encode($msg['id']),
                'btntxt' => '查看详情',
            ],
        ]);

        return $res['errcode'] == 0;
    }
}
