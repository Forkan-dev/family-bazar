<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearOldCartHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-old-cart-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired cart history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\CartHistory::expired()->delete();
        $this->info('Expired cart history cleared.');
    }
}
