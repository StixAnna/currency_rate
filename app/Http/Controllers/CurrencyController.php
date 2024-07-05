<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CurrencyController extends Controller
{
    // use AuthorizesRequests, ValidatesRequests;

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

        // return response()->json($spons_currs); //debug

        $arr = $spons_currs -> data;

        // return response()->json($arr); //debug

        // schet every curr
        foreach ($arr as $currency){
            $currency -> rateUsd *= 1.02;
            $ourcurr_price [] = $currency;

        }

        // Save sounds to the cache for future requests
        // Redis::set('currencys', json_encode($ourcurr_price));

        // return response()->json($ourcurr_price);
        return response()->json($ourcurr_price, 200, ['status' => 'success']);
    }

}
