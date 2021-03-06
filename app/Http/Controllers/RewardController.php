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
        ])->get(ENV('HELIUM_API_ENDPOINT').'/oracle/prices/current');
        $price_data = $response->json();

        $current_price = $price_data['data']['price'];
        
        $whole_num = substr($current_price, 0, 2);
        $decimal = round((substr($current_price, 2, 3) * .001), 2);
        $price = $whole_num + $decimal;
        
        $your_earnings = .041;

        $earnings = $price * $your_earnings;
        echo 'Currently valued at $' . round($earnings, 2) . '<br/>';
        echo 'Your Earnings $' . $your_earnings. '<br/>';
        echo 'Current Price $' . $price . '<br/>';
    }
}
