<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotspots;
use Illuminate\Support\Facades\Http;
use App\Jobs\updateHotspots;

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
        $hotspots = Hotspots::orderBy('last_active', 'ASC')->orderBy('day_earnings', 'DESC')->get();

        return view('actions.activity', compact('hotspots'));
    }

    public function testSms() {
        $basic  = new \Nexmo\Client\Credentials\Basic(ENV('NEXMO_KEY'), ENV('NEXMO_SECRET'));
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => '17347702351',
            'from' => ENV('NEXMO_FROM'),
            'text' => 'This is a test'
        ]);
    }
}
