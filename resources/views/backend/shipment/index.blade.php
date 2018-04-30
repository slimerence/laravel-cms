@extends('layouts.backend')
@section('content')
    <div id="">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.shipment') }} {{ trans('admin.mgr') }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/shipment/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.shipment_rule') }}</a>
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>目的地国家</th>
                    <th>省份</th>
                    <th>地区邮政编码</th>
                    <th>基础费用</th>
                    <th>每公斤运费</th>
                    <th>使用客户群</th>
                    <th>减免订单金额</th>
                    <th>状态</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($deliveryFees as $key=>$value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->country }}</td>
                        <td>{{ $value->state }}</td>
                        <td>{{ $value->postcode }}</td>
                        <td>${{ $value->basic }}</td>
                        <td>${{ $value->price_per_kg }}/KG</td>
                        <td>{{ \App\Models\Utils\UserGroup::RoleName($value->target_customer_group) }}</td>
                        <td>${{ $value->min_order_total }}</td>
                        <td>{{ $value->status?'有效':'无效' }}</td>
                        <td>
                            <a class="button is-small" href="{{ url('backend/shipment/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/shipment/delete/'.$value->id) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection