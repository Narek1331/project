<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlpineProjectService;

class GetClickCountsFromAlpineProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-click-counts-from-alpine-project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(AlpineProjectService::class)->syncClickCounts();
    }
}
