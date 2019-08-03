<?php
if(!function_exists('executeApi')){

    function executeApi($method, array $req = array(), \App\User $user) {
        // API settings
        $key = $user->email; // your API-key
        $secret = $user->secret; // your Secret-key
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
        curl_setopt($ch, CURLOPT_URL, env('API_DOMAIN_URL').'/api/tapi/');
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

if(!function_exists('getPrice')){
    function getPrice($code){
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '1',
            'convert' => strtoupper($code)
        ];

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: '.env('COINMARKETCAP_API_KEY')
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
// Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        $res = json_decode($response, true); // print json decoded response
        return $res['data'][0]['quote'][strtoupper($code)];
    }
}

if(!function_exists('getLatestPrice')){
    function getLatestPrice($code){
        $rate = \App\Exchange::where('code','=',$code)->orderBy('id','desc')->first();
        if(!is_null($rate)){
            return $rate->rate;
        } else {
            return 0;
        }
    }
}