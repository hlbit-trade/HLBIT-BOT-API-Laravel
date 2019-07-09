<?php

namespace App\Console\Commands;

use App\Exchange;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PriceUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto update price in coin marketcap';

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
        $btc_price = getLatestPrice('usd');
        $eth = getLatestPrice('eth');
        $eth_price = $btc_price['price'] / $eth['price'];

        $exchange = new Exchange();
        $exchange->code = 'btc';
        $exchange->rate = $btc_price;
        $exchange->save();
        Log::info('price btc updated to '.$btc_price['price']);

        $exchange2 = new Exchange();
        $exchange2->code = 'eth';
        $exchange2->rate = $eth_price;
        $exchange2->save();
        Log::info('price eth updated to '.$eth_price);
    }
}
