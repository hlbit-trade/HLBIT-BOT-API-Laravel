<?php

namespace App\Console\Commands;

use App\LogActivity;
use App\Setting;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BotRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runing bot based on setting';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $setting = Setting::where('status',Setting::STATUS_ACTIVE)->get();
        foreach($setting as $ini){
            $user = User::find($ini->user_id);

            $url = 'https://staging.hlbit.trade/api/ticker/'.$ini->pair;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $req = curl_exec($ch);
            $data = json_decode($req, true);
            if($data['status'] != 1){
                $log = new LogActivity();
                $log->status = $data['status'];
                $log->message = $data['error'];
                $log->save();
            }
            $value_24hr = $data['ticker']['last_24h_change'];
            $value_price = $data['ticker']['last'];
            $crypto_balance = 0;
            $fiat_balance = 0;
            $amount = 0;
            $price = 0;

            $crypto = executeApi('getBalance',['type'=>'crypto'],$user);
            if($crypto['status'] != 1){
                $log = new LogActivity();
                $log->status = $crypto['status'];
                $log->message = $crypto['error'];
                $log->save();
            }
            foreach ($crypto['data'] as $its){
                if($its['code'] == $data['ticker']['coin']){
                    $crypto_balance = $its['balance'];
                }
            }

            $fiat = executeApi('getBalance',['type'=>'fiat'],$user);
            if($fiat['status'] != 1){
                $log = new LogActivity();
                $log->status = $fiat['status'];
                $log->message = $fiat['error'];
                $log->save();
            }
            foreach ($fiat['data'] as $its){
                if($its['code'] == $data['ticker']['pair']){
                    $fiat_balance = $its['balance'];
                }
            }

            if($ini->type == 'sell'){
                $amount += $ini->amount * $crypto_balance / 100;
            } else {
                $amount += $ini->amount * $fiat_balance / 100;
            }

            if($ini->type_price != 'none'){
                if($ini->type_price == 'same'){
                    if($ini->value_price == $value_price){
                        $price = $value_price;
                    } else {
                        break;
                    }
                } elseif($ini->type_price == 'more'){
                    if($ini->value_price < $value_price){
                        $price = $value_price;
                    } else {
                        break;
                    }
                } elseif($ini->type_price == 'less'){
                    if($ini->value_price > $value_price){
                        $price = $value_price;
                    } else {
                        break;
                    }
                }
            }


            if($ini->type_24hr != 'none'){
                if($ini->type_24hr == 'same'){
                    if($ini->value_24hr == $value_24hr){
                        $price = $value_price;
                    } else {
                        break;
                    }
                } elseif($ini->type_24hr == 'more'){
                    if($ini->value_24hr < $value_24hr){
                        $price = $value_price;
                    } else {
                        break;
                    }
                } elseif($ini->type_24hr == 'less'){
                    if($ini->value_24hr > $value_24hr){
                        $price = $value_price;
                    } else {
                        break;
                    }
                }
            }

            $exec = executeApi('trade',
                [
                    'pair'=>$ini->pair,
                    'type'=>$ini->type,
                    'price'=>$price,
                    'amount'=>$amount
                ],$user);

            $log = new LogActivity();
            $log->status = $exec['status'];
            $log->message = $exec['status'] == 1 ? $exec['data']['message']:$exec['error'];
            $log->save();
            Log::info('pair : '.$ini->pair.' | type : '.$ini->type.' | price : '.$price.' | amount : '.$amount.' | crypto_balance : '.$crypto_balance.' | fiat_balance : '.$fiat_balance);
            if($ini->repeat == 0){
                $set = Setting::find($ini->id);
                $set->status = Setting::STATUS_DONE;
                $set->save();
            }
        }
    }
}
