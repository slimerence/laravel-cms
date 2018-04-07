<div class="content-block">
    <div class="content-title-line">
        <h3>Order Items ({{ count($order->orderItems) }})</h3>
    </div>
    <div class="content-detail-wrap content">
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">Price</th>
                <th scope="col">QTY</th>
                <th scope="col">Subtotal</th>
                <th scope="col">GST</th>
                <th scope="col">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->orderItems as $key=>$value)
                <tr>
                    <?php
                    $specialPrice = $value->product->getSpecialPriceGST();
                    $defaultPrice = $value->product->getDefaultPriceGST();
                    ?>
                    <td>{{ $key+1 }}</td>
                    <td>
                        {{ $value->product->name }}
                        <div class="option-notes">
                            {!! $value->notes !!}
                        </div>
                    </td>
                    <td>
                        @if($specialPrice)
                            <span>{{ config('system.CURRENCY') }} {{ $specialPrice }}</span>
                            <span class="origin-price-txt text-danger">{{ config('system.CURRENCY') }} {{ $defaultPrice }}</span>
                        @else
                            <span>{{ config('system.CURRENCY') }} {{ $defaultPrice }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $value->quantity }}
                    </td>
                    <td>
                        {{ config('system.CURRENCY'). ' '.number_format($value->subtotal/1.1,2) }}
                    </td>
                    <td>
                        {{ config('system.CURRENCY'). ' '.number_format($value->subtotal-$value->subtotal/1.1,2) }}
                    </td>
                    <td>
                        {{ config('system.CURRENCY'). ' '.number_format($value->subtotal,2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>{{ count($order->orderItems)+1 }}</td>
                <td colspan="2">Delivery Fee</td>
                <td>
                    {{ $order->delivery_charge>0 ? 1 : 'Free' }}
                </td>
                <td>
                    {{ config('system.CURRENCY') }} {{ $order->delivery_charge>0 ? number_format($order->delivery_charge/1.1,2) : '0.00' }}
                </td>
                <td>
                    {{ config('system.CURRENCY') }} {{ $order->delivery_charge>0 ? number_format($order->delivery_charge-$order->delivery_charge/1.1,2) : '0.00' }}
                </td>
                <td>
                    {{ config('system.CURRENCY') }} {{ $order->delivery_charge>0 ? number_format($order->delivery_charge,2) : '0.00' }}
                </td>
            </tr>
            <tr class="em">
                <?php
                    $total_final = $order->getTotalFinal();
                ?>
                <td></td>
                <td></td>
                <td>

                </td>
                <td>
                    Total
                </td>
                <td>
                    {{ config('system.CURRENCY'). ' '.number_format($total_final/1.1,2) }}
                </td>
                <td>
                    {{ config('system.CURRENCY'). ' '.number_format($total_final-$total_final/1.1,2) }}
                </td>
                <td>
                    {{ config('system.CURRENCY'). ' '.number_format($total_final,2) }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>