<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\Hotspots;
use Illuminate\Support\Facades\Log;

class RewardController extends Controller
{
    public function getRewards() {
        $response = Http::withHeaders([
            'user-agent' => 'Helium Script'
        ])->get('https://api.helium.io/v1/oracle/prices/current');
        $price_data = $response->json();

        $current_price = $price_data['data']['price'];
        
        $whole_num = substr($current_price, 0, 2);
        $decimal = round((substr($current_price, 2, 3) * .001), 2);
        $price = $whole_num + $decimal;
        
        $your_earnings = .041;

        $earnings = $price * $your_earnings;
        dd('Currently valued at $' . round($earnings, 2));
    }
}
