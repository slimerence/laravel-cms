<?php

namespace App\Listeners;

use App\Events\OrderInvoiceIssued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderInvoiceIssuedEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderInvoiceIssued  $event
     * @return void
     */
    public function handle(OrderInvoiceIssued $event)
    {
        //
    }
}
