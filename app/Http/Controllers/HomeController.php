<?php

namespace App\Http\Controllers;

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
        if ($user['openid'] == null) {
            session()->flash('status', '你已经绑定过微信了');
        }
        $app = app('wechat.official_account');
        $result = $app->qrcode->temporary(Auth::id(), 600);
        $url = $app->qrcode->url($result['ticket']);
        return view('bind', ['qr_url' => $url]);
    }

}
