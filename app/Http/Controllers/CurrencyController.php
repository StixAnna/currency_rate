<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CurrencyController extends Controller
{
    // use AuthorizesRequests, ValidatesRequests;
    function ComparerateUsd($a, $b) //   kalkalich
{
    return $b['attrs']['ctr'] <=> $a['attrs']['ctr'];
}

    //display all currencyes, json return
    public function index(){
        // Check if sounds exist in the cache
        // $spons_currs = Redis::get('currencys');

        // // If sounds are found in the cache, return them
        // if ($spons_currs) {
        //     return response()->json(json_decode($spons_currs));
        // }

        // If currrencys are not found in the cache, fetch them from the jsonurl
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, 'https://api.coincap.io/v2/rates');
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $spons_currs = json_decode(curl_exec($curlSession));
        curl_close($curlSession);

        $arr = $spons_currs -> data;
        $ourcurr_price = [];
        $return['status'] = 'success';
        $return['code'] = 200;

        // schet every curr
        foreach ($arr as $currency){
            $curmane = $currency -> symbol;
            $curprice = $currency -> rateUsd;
            $value[$curmane] = $curprice * 1.02;
            $ourcurr_price = array_merge($ourcurr_price, $value);
        }
        // usort($ourcurr_price, 'ComparerateUsd'); //kalkalich

        $return['data'] = $ourcurr_price;

        // Save sounds to the cache for future requests
        // Redis::set('currencys', json_encode($ourcurr_price));

        return response()->json($return, 200, ['status' => 'success']);
    }
}
