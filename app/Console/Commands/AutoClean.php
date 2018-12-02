<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Smartbro\Models\Reservation;

class AutoClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:autoclean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Unpaid Reservations';

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
     * @return mixed
     */
    public function handle()
    {
        Reservation::AutoDeleteUnpaid();
    }
}
