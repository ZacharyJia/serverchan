<?php

namespace App\Http\Controllers;

use App\Msg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next){
            session()->forget('status');
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function bind()
    {
        $user = Auth::user();
        if ($user['openid'] != null) {
            session()->put('status', '你已经绑定过微信了');
        }
        $app = app('wechat.official_account');
        $result = $app->qrcode->temporary(Auth::id(), 600);
        $url = $app->qrcode->url($result['ticket']);
        return view('bind', ['qr_url' => $url]);
    }

    public function sckey()
    {
        $user = Auth::user();
        if ($user['sckey'] == null) {
            session()->put('status', '你还没有sckey');
        }
        return view('sckey', ['sckey' => $user['sckey']]);
    }

    public function gen()
    {
        $user = Auth::user();
        $user['sckey'] = str_random(32);
        $user->save();
        return redirect('/sckey');
    }

    public function msg_list()
    {
        $msgs = Auth::user()->msgs()->paginate(20);

        return view('list', ['msgs' => $msgs]);
    }

    public function bind_work()
    {
        $work_id = Auth::user()->work_id;
        return view('bind_work', ['work_id' => $work_id]);
    }

    public function do_bind_work(Request $request)
    {
        $work_id = $request->input('work_id');
        if (empty($work_id)) {
            session()->put('status', '企业微信ID验证失败');
            return view('bind_work', ['work_id' => '']);
        }
        $app = app('wechat.work');
        $res = $app->user->get($work_id);
        if ($res['errcode'] != 0) {
            session()->put('status', '企业微信ID验证失败');
            return view('bind_work', ['work_id' => '']);
        }
        $user = Auth::user();
        $user['work_id'] = $work_id;
        $user->save();

        session()->put('status', '企业微信ID验证成功！');
        return view('bind_work', ['work_id' => $work_id]);
    }

    public function unbind_wechat()
    {
        $user = Auth::user();
        $user['openid'] = null;
        $user->save();
        return redirect('/bind');
    }

    public function unbind_work()
    {
        $user = Auth::user();
        $user['work_id'] = null;
        $user->save();
        return redirect('/bind_work');
    }


}
