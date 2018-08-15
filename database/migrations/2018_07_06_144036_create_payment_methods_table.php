<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Settings\PaymentMethod;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->smallInteger('method_id');
            $table->text('api_token')->nullable();
            $table->text('api_token_test')->nullable();
            $table->text('api_secret_test')->nullable();
            $table->text('api_secret')->nullable();

            $table->text('hook_success')->nullable();
            $table->text('hook_error')->nullable();
            $table->text('notes')->nullable();
            $table->string('mode')->default(PaymentMethod::MODE_OFF);
        });

        PaymentMethod::Persistent([
            'name'=>'Stripe',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_STRIPE,
        ]);

        PaymentMethod::Persistent([
            'name'=>'PayPal Express',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_PAYPAL,
        ]);

        PaymentMethod::Persistent([
            'name'=>'Place Order',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_PLACE_ORDER,
        ]);

        PaymentMethod::Persistent([
            'name'=>'PayPal Pro',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_PAYPAL_PRO,
        ]);

        PaymentMethod::Persistent([
            'name'=>'WeChat Pay - 微信支付',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_WECHAT,
        ]);

        PaymentMethod::Persistent([
            'name'=>'AliPay - 支付宝',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_ALIPAY,
        ]);

        PaymentMethod::Persistent([
            'name'=>'Apple Pay',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_APPLE_PAY,
        ]);

        PaymentMethod::Persistent([
            'name'=>'Credit Card - Bank Gateway',
            'method_id'=>\App\Models\Utils\PaymentTool::$TYPE_CREDIT_CARD,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
