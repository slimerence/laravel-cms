<?php

namespace App\Jobs\Payment;

use App\Models\Settings\PaymentMethod;
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
    private $method;

    /**
     * PayPal constructor.
     * @param Order $order
     * @param PaymentMethod $method
     */
    public function __construct(Order $order, PaymentMethod $method)
    {
        $this->order = $order;
        $this->method = $method;
    }

    /**
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle()
    {
        $payPalExpress = new PayPalTool($this->method);
        $response = $payPalExpress->purchase($this->order);
        if($response->isRedirect()){
            $response->redirect();
        }
        return redirect()->back()->with([
            'message'=>$response->getMessage()
        ]);
    }
}
