@extends('layouts.backend')
@section('content')
    <div class="container" id="my-orders-manager-app">
        <br>
            <div class="columns">
                <div class="column">
                    <h2 class="is-size-4">{{ trans('admin.menu.orders') }} {{ trans('admin.mgr') }} ({{ $orders->total() }})</h2>
                </div>
                <div class="column">
                    <el-form ref="form" label-width="80px">
                        <el-autocomplete
                                style="width: 500px;margin-right: 20px;"
                                v-model="orderKeyword"
                                :fetch-suggestions="orderSearchAsync"
                                placeholder="Search Orders"
                                icon="el-icon-search"
                                popper-class="order-search-auto-complete"
                                :trigger-on-focus="false"
                                :select-when-unmatched="true"
                                @select="handleResultSelect"
                        ></el-autocomplete>
                    </el-form>
                </div>
            </div>
            <div class="container">
                <table class="table full-width is-hoverable">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Place Order</th>
                        <th scope="col">Date</th>
                        <th scope="col">Group</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Items</th>
                        <th scope="col">Total(GST)</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $key=>$value)
                        <tr>
                            <th scope="row">{{ $value->serial_number }}</th>
                            <td>{{ $value->place_order_number }}</td>
                            <td>{{ substr($value->created_at, 0, 11) }}</td>
                            <td>
                                {{ \App\Models\Utils\UserGroup::RoleName($value->customer->role) }}
                            </td>
                            <td>
                                <a href="{{ url('backend/orders/list_by_pm/'.$value->user_id) }}">{{ $value->customer->name }}</a>
                            </td>
                            <td>
                                <a href="#" v-on:click="showItems('{{$value->uuid}}')">
                                    <span class="badge badge-pill badge-light">{{ count($value->orderItems) }}</span>
                                </a>
                            </td>
                            <td>{{ config('system.CURRENCY'). ' '.number_format($value->total,2) }}</td>
                            <td>{!! \App\Models\Utils\OrderStatus::GetName($value->status) !!}</td>
                            <td>
                                <a href="{{ url('backend/orders/view/'.$value->id) }}" class="button is-primary is-small">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
    </div>
@endsection
