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