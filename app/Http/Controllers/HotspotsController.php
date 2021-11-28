<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotspots;
use Illuminate\Support\Facades\Http;

class HotspotsController extends Controller
{

    public function newHotspotForm() {
        return view('admin.addHotspot');
    }

    public function addNewHotspot(Request $request) {

        $hotspots = new Hotspots;
        $hotspots->hotspot_address = $request->hotspot_address;
        $hotspots->wallet_address = $request->wallet_address;
        $hotspots->phone = $request->phone_number;

        $hotspots->save();
        return redirect()->back()->with('success', 'Hotspot has been added!');
    }

    public function getLastActivity() {
        $hotspots = Hotspots::all();
  
        foreach($hotspots as $hotspot) {
            $response = Http::get('https://api.helium.io/v1/hotspots/'. $hotspot->hotspot_address);
            $hotspot_data = $response->json();

            $name = ucwords(str_replace("-", " ", $hotspot_data['data']['name']));
            $current_block = $hotspot_data['data']['last_change_block'];
            $block_target = $hotspot_data['data']['block'];
            $last_active = $block_target - $current_block;

            $hotspot->current_block = $current_block;
            $hotspot->block_target = $block_target;
            $hotspot->last_active = $last_active;
            $hotspot->hotspot_name = $name;

            $earnings = Http::withHeaders([
                'user-agent' => 'Helium Script'
            ])->get('https://api.helium.io/v1/hotspots/'.$hotspot->hotspot_address.'/rewards/sum', [
                'min_time' => '-7 day',
                'bucket' => 'day',
            ]);
            $hotspot_earnings = $earnings->json();

            $hotspot->day_earnings = round($hotspot_earnings['data'][0]['total'], 2);
            
            $hotspot->save();
            
        }

        $hotspots = Hotspots::orderBy('last_active', 'ASC')->orderBy('day_earnings', 'DESC')->get();

        return view('actions.activity', compact('hotspots'));
    }
}
