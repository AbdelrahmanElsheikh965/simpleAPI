<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class forceDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:force-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command force-deletes all the softly-deleted posts for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Post::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30)->endOfDay())->forceDelete();

        info("softly-deleted posts for more than 30 days are deleted");
    }
}
