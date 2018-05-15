@extends(_get_frontend_layout_path('catalog'))
@section('content')
    <div class="container pt-40  pl-20 pr-20" id="cart-view-manager-app">
        <div class="content">
            <div class="panel">
                <br>
                <h4 class="is-size-3 has-text-link">
                    {{ trans('cart.Summary') }}:
                </h4>
            </div>
            <div class="panel">
                <el-table
                        ref="cartTable"
                        :data="cartData"
                        style="width: 98%">
                    <el-table-column
                            label="" width="150">
                        <template slot-scope="scope">
                            <a :href="'frontend/product/'+scope.row.id">
                                <img :src="scope.row.options.thumbnail" class="product-thumb">
                            </a>
                        </template>
                    </el-table-column>

                    <el-table-column
                            property="name"
                            label="{{ trans('cart.Product') }}" width="300">
                        <template slot-scope="scope">
                            <a :href="'frontend/product/'+scope.row.id" class="product-name">
                                @{{ scope.row.name }}
                            </a>
                            <div v-for="(item, idx) in scope.row.options" class="product-option-item">
                                <div v-if="(idx==='colour' && item !==null)" class="p-color-in-cart">
                                    <span class="label">@{{ item.name }}: </span>
                                    <span class="price">@{{ _convertToCurrency(item.extra_money) }}</span>
                                    <div v-if="item.type==={{ \App\Models\Utils\ColourTool::$TYPE_HEX_CODE }}" class="color-box" :style="{background:item.value}">&nbsp;</div>
                                    <img v-if="item.type==={{ \App\Models\Utils\ColourTool::$TYPE_IMAGE }}" class="color-thumb" :src="item.imageUrl">
                                </div>
                                <p v-if="idx!=='colour'&&idx!=='thumbnail'&&(item !==null)">
                                    <span class="label">@{{ item.name }}:</span> @{{ item.value }}
                                </p>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column
                            property="price"
                            label="{{ trans('cart.Price') }}">
                        <template slot-scope="scope">
                            <span>{{config('system.CURRENCY')}} @{{ scope.row.price.toFixed(2) }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column
                            property="qty"
                            label="{{ trans('cart.Quantity') }}">
                        <template slot-scope="scope">
                            <el-input-number v-bind:min="1" size="small" v-model="qtys[scope.$index]" v-on:input="updateQuantity(scope.$index)"></el-input-number>
                        </template>
                    </el-table-column>

                    <el-table-column
                            label="{{ trans('cart.Subtotal') }}">
                        <template slot-scope="scope">
                            <span>{{config('system.CURRENCY')}} @{{ _calcSubTotal(scope.row).toFixed(2) }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column label="" width="70">
                        <template slot-scope="scope">
                            <el-button
                                    size="small"
                                    type="danger"
                                    icon="el-icon-delete"
                                    class="button is-danger"
                                    v-loading.fullscreen.lock="fullscreenLoading"
                            @click="handleDelete(scope.$index, scope.row)"></el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <div class="columns checkout-bar">
            <div class="column">
                <p class="total-txt has-text-right">
                    {{ trans('cart.Total') }}: @{{ cartTotalText }}
                </p>
            </div>
            <div class="column">
                <el-button type="primary" size="large" v-on:click="checkout">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;{{ trans('cart.Checkout') }} <i class="el-icon-arrow-right el-icon--right"></i>
                </el-button>
            </div>
        </div>
    </div>
@endsection