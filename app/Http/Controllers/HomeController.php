<?php

namespace App\Http\Controllers;

use App\LogActivity;
use App\Setting;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showLog(){
        $log = LogActivity::orderBy('created_at','desc')->get();
        return view('log',[
            'log'=>$log
        ]);
    }
    public function index()
    {
        $data = $this->execute('getInfo');
        if($data['status'] != 1){
            $log = new LogActivity();
            $log->status = $data['status'];
            $log->message = $data['error'];
            $log->save();
            return redirect('log');
        }
        return view('home',[
            'user' => $data['data']
        ]);
    }

    public function trade(){
        $market = 'btcusd';
        if(isset($_GET['pair'])){
            $market = $_GET['pair'];
        }
        $url = 'https://staging.hlbit.trade/api/ticker/'.$market;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $req = curl_exec($ch);
        $data = json_decode($req, true);
        if($data['status'] != 1){
            $log = new LogActivity();
            $log->status = $data['status'];
            $log->message = $data['error'];
            $log->save();
            return redirect('log');
        }

        $fiat = $this->execute('getBalance',['type'=>'fiat']);
        if($fiat['status'] != 1){
            $log = new LogActivity();
            $log->status = $fiat['status'];
            $log->message = $fiat['error'];
            $log->save();
            return redirect('log');
        }

        $crypto = $this->execute('getBalance',['type'=>'crypto']);
        if($crypto['status'] != 1){
            $log = new LogActivity();
            $log->status = $crypto['status'];
            $log->message = $crypto['error'];
            $log->save();
            return redirect('log');
        }

        return view('trade',[
            'data'=>$data['ticker'],
            'balance_fiat' => $fiat['data'],
            'balance_crypto' => $crypto['data'],
            'setting'=>Setting::all()
        ]);
    }

    public function settingSave(Request $request){
        $data = new Setting();
        $request['user_id'] = auth()->user()->id;
        $data->create($request->all());

        return redirect('/trade');
    }

    public function history(){
        $market = 'btcusd';
        if(isset($_GET['pair'])){
          $market = $_GET['pair'];
        }
        $data = $this->execute('listOrder',['pair'=>$market]);
        if($data['status'] != 1){
            $log = new LogActivity();
            $log->status = $data['status'];
            $log->message = $data['error'];
            $log->save();
            return redirect('log');
        }
        return view('history',[
            'market'=>$market,
            'data'=>$data['data']
        ]);
    }

    public function cancelOrder(Request $request){
        $request->validate([
            'id'=>'required'
        ]);
        $data = $this->execute('cancelOrder',['order_id'=>$request->id]);

        if($data['status'] != 1){
            $log = new LogActivity();
            $log->status = $data['status'];
            $log->message = $data['error'];
            $log->save();
            return redirect('log');
        }
        return redirect('order-list');
    }

    private function execute($method, array $req = array()) {
        // API settings
        $key = auth()->user()->email; // your API-key
        $secret = auth()->user()->secret; // your Secret-key
        $req['method'] = $method;

        $post_data = http_build_query($req, '', '&');

        $headers = array(
            'appkey: '.$key,
            'secret: '.$secret,
        );

        // our curl handle (initialize if required)
        static $ch = null;
        if (is_null($ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36
		'.php_uname('s').'; PHP/'.phpversion().')');
        }
        curl_setopt($ch, CURLOPT_URL, 'https://staging.hlbit.trade/api/tapi/');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // run the query
        $res = curl_exec($ch);

        if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));

        $dec = json_decode($res, true);

        if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists: '.$res);

        curl_close($ch);
        $ch = null;
        return $dec;
    }
}
