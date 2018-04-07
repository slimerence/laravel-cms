@extends('layouts.backend')
@section('content')
    <div class="container pt-40" id="view-orders-manager-app">
        <div class="box pt-4">
            <div class="columns">
            <div class="column">
                <h4>
                    Order #: {{ $order->serial_number }} {!! \App\Models\Utils\OrderStatus::GetName($order->status) !!}
                </h4>
            </div>
            <div class="column">
                <div class="btn-group float-right order-actions-bar" role="group" aria-label="Actions">
                    <a class="button is-primary" href="{{ url('backend/dashboard') }}" role="button">
                        <i class="fas fa-arrow-left"></i>&nbsp;Back
                    </a>
                    <button class="button is-{{ $order->status==\App\Models\Utils\OrderStatus::$APPROVED ? 'primary':'secondary' }} btn-sm"
                           v-on:click="issueInvoice({{ $order->id }})" {{ $order->status==\App\Models\Utils\OrderStatus::$APPROVED ? null:'disabled' }}>
                        <i class="far fa-file-alt"></i>&nbsp;Invoice
                    </button>
                    <button class="button is-{{ $order->status==\App\Models\Utils\OrderStatus::$INVOICED ? 'primary':'secondary' }} btn-sm"
                       v-on:click="issueShipped({{ $order->id }})" {{ $order->status==\App\Models\Utils\OrderStatus::$INVOICED ? null:'disabled' }}>
                        <i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Shipped
                    </button>
                    <button class="button is-{{ $order->status==\App\Models\Utils\OrderStatus::$DELIVERED ? 'primary':'secondary' }} btn-sm"
                        v-on:click="complete({{ $order->id }})" {{ $order->status==\App\Models\Utils\OrderStatus::$DELIVERED ? null:'disabled' }}>
                        <i class="far fa-check-square"></i>&nbsp;Complete
                    </button>
                </div>
            </div>
            </div>
        </div>
        <div class="box">
            <div class="columns">
                <div class="column">
                @include('backend.order.elements.summary')
                </div>
                <div class="column">
                @include('backend.order.elements.customer')
                </div>
            </div>
        </div>

        <div class="box">
            <div class="content">
                @include('backend.order.elements.order_items')
            </div>
        </div>
        <div class="box">
            <div class="columns">
                <div class="column">
                @include('backend.order.elements.shipment')
                </div>
                <div class="column">
                @include('backend.order.elements.notes')
                </div>
            </div>
        </div>

    </div>
@endsection
