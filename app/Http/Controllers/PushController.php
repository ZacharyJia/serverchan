<?php

namespace App\Http\Controllers;

use App\Msg;
use App\User;
use Illuminate\Http\Request;
use Hashids;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class PushController extends Controller
{
    public function push(Request $request, $sckey)
    {
        $user = User::where('sckey', $sckey)->first();
        if ($user != null) {

            $channel = $request->input('channel');
            if (empty($channel)) {
                if ($user['work_id'] != null) {
                    $channel = 'work';
                } else if ($user['bark_url'] != null) {
                    $channel = 'bark';
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
            $msg['channel'] = $channel;
            $msg->save();

            $url = $request->root() . '/detail/' . Hashids::encode($msg['id']);

            if ($channel == 'wechat') {
                $this->send_wechat($user, $msg, $url);
            } elseif ($channel == 'bark') {
                $this->send_bark($user, $msg);
            } else {
                $this->send_work($user, $msg, $url);
            }

            return ['res' => 'success', 'msg' => 'success'];
        } else {
            return ['res' => 'failed', 'msg' => 'sckey error!'];
        }
    }

    protected function send_wechat($user, $msg, $url)
    {
        $openid = $user['openid'];
        if ($openid == null) {
            return ['res' => 'failed', 'msg' => 'you have not bound wechat account'];
        }

        $app = app('wechat.official_account');
        $app->template_message->send([
            'touser' => $openid,
            'template_id' => env('WECHAT_OFFICIAL_ACCOUNT_TEMPLATE_ID'),
            'url' => $url,
            'data' => [
                'title' => $msg['title'],
                'content' => $msg['content'],
            ],
        ]);

        return true;
    }

    protected function send_work($user, $msg, $url)
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
                'url' => $url,
                'btntxt' => '查看详情',
            ],
        ]);

        return $res['errcode'] == 0;
    }

    protected function send_bark($user, $msg)
    {
        $bark_url = $user['bark_url'];
        if (empty($bark_url)) {
            return ['res' => 'failed', 'msg' => 'you have not set bark url yet!'];
        }

        $url = $bark_url . '/' . urlencode($msg['title']) . '/' . urlencode($msg['content']);
        $client = new Client();
        $response = $client->get($url);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $res = json_decode($body, true);

            if ($res['code'] == 200) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
