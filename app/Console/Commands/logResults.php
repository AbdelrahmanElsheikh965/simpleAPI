<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class logResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:log-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command makes HTTP Request
    to this endpoint (https://randomuser.me/api/) and log only the results object in the response';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://randomuser.me/api/');
        info($response->body('results'));
    }
}
