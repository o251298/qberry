<?php

namespace App\Console\Commands;

use App\Models\BlockBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:block';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('booking')->info('Start updating status block_booking');
        BlockBooking::updateStatus();
        Log::channel('booking')->info('End updating status block_booking');
        return 0;
    }
}
