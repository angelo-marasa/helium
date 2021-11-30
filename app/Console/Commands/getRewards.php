<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\Hotspots;
use Illuminate\Support\Facades\Log;

class getRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rewards:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of rewards';

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
                //-- Initiate a new hotspot instance
                $hotspots = Hotspots::all();
        
                //-- Loop through hotspots
                foreach ($hotspots as $hotspot) {
                        $rewards = Http::withHeaders([
                        'user-agent' => 'Helium Script'
                    ])->get('https://api.helium.io/v1/hotspots/'.$hotspot['hotspot_address'].'/activity', [
                        'limit' => '5',
                    ]);
                        $hotspot_earnings = $rewards->json();
        
                        $rewards = Http::withHeaders([
                        'user-agent' => 'Helium Script'
                    ])->get('https://api.helium.io/v1/hotspots/'.$hotspot['hotspot_address'].'/activity', [
                        'limit' => '5',
                        'cursor' => $hotspot_earnings['cursor']
                    ]);
                        $hotspot_earnings = $rewards->json();
        
                        foreach ($hotspot_earnings['data'] as $earnings) {
                            if ($earnings['type'] == 'rewards_v2') {
                                $reward_id = $earnings['hash'];
                                $total_earnings = 0;
                    
                                foreach ($earnings['rewards'] as $r) {
                                    $total_earnings += round((substr($r['amount'], 0, 3) * .001), 2);
                                }
                            
                                //-- Check the database and see if a record exists
                                $hotspot_rewards = Reward::where('hotspot', '=', $hotspot['hotspot_address'])->where('reward_id', '=', $reward_id)->count();
        
                                //-- No record for this reward. I'll log it and send a text message
                                if ($hotspot_rewards == 0) {
                                    $rw = new Reward;
        
                                    $rw->hotspot = $hotspot['hotspot_address'];
                                    $rw->reward_id = $reward_id;
                                    $rw->amount = $total_earnings;
                        
                                    $rw->save();
                                    Log::info('----------------------------------------------------');
                                    Log::info($hotspot['hotspot_address']);
                                    Log::info($hotspot['phone']);
                                    Log::info($reward_id);
                                    Log::info($total_earnings);
                                    Log::info('----------------------------------------------------');
        
                                    $basic  = new \Nexmo\Client\Credentials\Basic(ENV('NEXMO_KEY'), ENV('NEXMO_SECRET'));
                                    $client = new \Nexmo\Client($basic);
        
                                    $message = $client->message()->send([
                                        'to' => '1'.$hotspot['phone'],
                                        'from' => ENV('NEXMO_FROM'),
                                        'text' => $hotspot['hotspot_name'] . ' has earned a reward of ' . $total_earnings . ' HNT'
                                    ]);
                                }
                            }
                        }
                }
        return Command::SUCCESS;
    }
}
