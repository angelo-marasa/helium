<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hotspots;
use Illuminate\Support\Facades\Http;

class UpdateHotspotData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotspots:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the details of each stored Hotspot';

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
     * @return int
     */
    public function handle()
    {
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
        return Command::SUCCESS;
    }
}
