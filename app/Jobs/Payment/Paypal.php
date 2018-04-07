<?php

namespace App\Jobs\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order\Order;
use App\Models\Utils\Payment\PayPalTool;

class Paypal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;
    private $libName;

    /**
     * Paypal constructor.
     * @param Order $order
     * @param string $libName
     */
    public function __construct(Order $order, $libName = 'PayPal_Express')
    {
        $this->order = $order;
        $this->libName = $libName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payPalExpress = new PayPalTool($this->libName);
        $response = $payPalExpress->purchase($this->order);
        if($response->isRedirect()){
            $response->redirect();
        }
        return redirect()->back()->with([
            'message'=>$response->getMessage()
        ]);
    }
}
