<?php

namespace App\Console\Commands;

use App\LogActivity;
use App\Setting;
use App\User;
use Illuminate\Console\Command;

class AutoCancel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Cancel if order pending > 10';

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
        $setting = Setting::where('status','=',Setting::STATUS_ACTIVE)->get();
        foreach($setting as $ini){
            $user = User::find($ini->user_id);
            $list_order = executeApi('listOrder',['pair'=>$ini->pair],$user);
            $count_pending = 0;
            foreach ($list_order['data'] as $its){
                if($its['status'] == 'pending'){
                    $count_pending += 1;
                }
            }
            if($count_pending > 10){
                foreach ($list_order['data'] as $its){
                    if($its['status'] == 'pending'){
                        $cancel = executeApi('cancelOrder',['order_id'=>$its['id']],$user);
                        $log = new LogActivity();
                        $log->status = $cancel['status'];
                        $log->message = $cancel['status'] == 1 ? $cancel['data']['message']:$cancel['error'];
                        $log->save();
                        break;
                    }
                }
            }
        }
    }
}
