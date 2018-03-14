@extends('layouts.backend')
@section('content')
    <div class="container" id="view-orders-manager-app">
        <div class="row pt-4">
            <div class="col">
                <h4>
                    Order #: {{ $order->serial_number }} {!! \App\Models\Utils\OrderStatus::GetName($order->status) !!}
                </h4>
            </div>
            <div class="col">
                <div class="btn-group float-right order-actions-bar" role="group" aria-label="Actions">
                    <a class="btn btn-primary btn-sm" href="{{ url('backend/dashboard') }}" role="button">
                        <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;Back
                    </a>
                    <button class="btn btn-{{ $order->status==\App\Models\Utils\OrderStatus::$APPROVED ? 'primary':'secondary' }} btn-sm"
                           v-on:click="issueInvoice({{ $order->id }})" {{ $order->status==\App\Models\Utils\OrderStatus::$APPROVED ? null:'disabled' }}>
                        <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Invoice
                    </button>
                    <button class="btn btn-{{ $order->status==\App\Models\Utils\OrderStatus::$INVOICED ? 'primary':'secondary' }} btn-sm"
                       v-on:click="issueShipped({{ $order->id }})" {{ $order->status==\App\Models\Utils\OrderStatus::$INVOICED ? null:'disabled' }}>
                        <i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Shipped
                    </button>
                    <button class="btn btn-{{ $order->status==\App\Models\Utils\OrderStatus::$DELIVERED ? 'primary':'secondary' }} btn-sm"
                        v-on:click="complete({{ $order->id }})" {{ $order->status==\App\Models\Utils\OrderStatus::$DELIVERED ? null:'disabled' }}>
                        <i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Complete
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @include('backend.order.elements.summary')
                @include('backend.order.elements.customer')
            </div>
        </div>

        <div class="row">
            <div class="col">
                @include('backend.order.elements.order_items')
            </div>
        </div>
        <div class="row">
            <div class="col">
                @include('backend.order.elements.shipment')
                @include('backend.order.elements.notes')
            </div>
        </div>

    </div>
@endsection
