@extends('layouts.backend')

@section('content')
    <div id="delivery-fee-form-app" class="content">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    运费
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/shipment') }}"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="content">
            <form method="POST" action="{{ url('backend/shipment/save') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $deliveryFee->id }}">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Country</label>
                            <div class="control">
                                <el-autocomplete
                                    class="inline-input"
                                    v-model="formData.country"
                                    name="country"
                                    :fetch-suggestions="querySearch"
                                    placeholder="目的地国家: Required"
                                    :trigger-on-focus="false"
                                    @select="handleSelect"
                                ></el-autocomplete>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">State</label>
                            <div class="control">
                                <input type="text" class="input{{ $errors->has('state') ? ' is-invalid' : '' }}"
                                       name="state" value="{{ $deliveryFee->state }}" placeholder="目的地国家省份: Optional">
                                @if ($errors->has('state'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Postcode</label>
                            <div class="control">
                                <input type="text" class="input{{ $errors->has('postcode') ? ' is-invalid' : '' }}"
                                       name="postcode" value="{{ $deliveryFee->postcode }}" placeholder="目的地国家邮政编码: Optional">
                                @if ($errors->has('postcode'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('postcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Base Price(不要输入$, 仅数字)</label>
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control">
                                            <a class="button is-static">
                                                $
                                            </a>
                                        </p>
                                        <p class="control is-expanded">
                                            <input type="text" class="input{{ $errors->has('basic') ? ' is-invalid' : '' }}"
                                                   name="basic" value="{{ $deliveryFee->basic ? $deliveryFee->basic : env('DOMESTIC_DELIVERY_FEE',10) }}" placeholder="基础价格: Required">
                                            @if ($errors->has('basic'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('basic') }}</strong>
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Fee/KG (不要输入$, 仅数字)</label>
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control">
                                            <a class="button is-static">
                                                $
                                            </a>
                                        </p>
                                        <p class="control is-expanded">
                                            <input type="text" class="input{{ $errors->has('price_per_kg') ? ' is-invalid' : '' }}"
                                                   name="price_per_kg" value="{{ $deliveryFee->price_per_kg ? $deliveryFee->price_per_kg : 0 }}" placeholder="超出部分每公斤运费: Optional">
                                            @if ($errors->has('price_per_kg'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('price_per_kg') }}</strong>
                                                </span>
                                            @endif
                                        </p>
                                        <p class="control">
                                            <a class="button is-static">
                                                /KG
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Fee Delivery (不要输入$, 仅数字)</label>
                            <div class="field-body">
                                <div class="field is-expanded">
                                    <div class="field has-addons">
                                        <p class="control">
                                            <a class="button is-static">
                                                Order total > $
                                            </a>
                                        </p>
                                        <p class="control is-expanded">
                                            <input type="text" class="input{{ $errors->has('min_order_total') ? ' is-invalid' : '' }}"
                                                   name="min_order_total" value="{{ $deliveryFee->min_order_total ? $deliveryFee->min_order_total :0 }}" placeholder="订单金额超过后减免运费: Optional">
                                            @if ($errors->has('min_order_total'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('min_order_total') }}</strong>
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">状态</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="status">
                                        <option value="1" {{ $deliveryFee->status ? 'selected' : null }}>立刻生效</option>
                                        <option value="0" {{ $deliveryFee->status ? null : 'selected' }}>置为无效</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">目标客户群</label>
                            <div class="control">
                                <div class="select full-width">
                                    <select class="full-width" name="target_customer_group">
                                        <option value="{{ \App\Models\Utils\UserGroup::$GENERAL_CUSTOMER }}"
                                                {{ $deliveryFee->target_customer_group==\App\Models\Utils\UserGroup::$GENERAL_CUSTOMER ? 'selected' : null }}>一般用户</option>
                                        <option value="{{ \App\Models\Utils\UserGroup::$WHOLESALE_CUSTOMER }}"
                                                {{ $deliveryFee->target_customer_group==\App\Models\Utils\UserGroup::$WHOLESALE_CUSTOMER ? 'selected' : null }}>批发商客户</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="control">
                        <button type="submit" class="button is-link">
                            <i class="fa fa-upload"></i>&nbsp;Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
