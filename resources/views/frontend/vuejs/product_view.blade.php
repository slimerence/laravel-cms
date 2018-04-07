<?php
// 计算价格
$thePrice = $product->getDefaultPriceGST();
$theSpecialPrice = $product->getSpecialPriceGST();
?>
<script>
    var ProductViewManager = new Vue({
        el: '#product-view-manager-app',
        data:{
            redirectToCartIfAddToCartSuccess:{{ config('system.VIEW_CART_AFTER_ADD_TO_ORDER_SUCCESS')?1:0 }},
            orderItem:{},
            invalidClassName: 'is-invalid',
            specialInvalidClassName: 'zbn-invalid-class', // 这个是一个特殊的class, 用来表示input不是valid
            /*
             * 价格相关的属性. 只是用来显示用, 真正的价格计算还是在服务器端
             * For display only. The final price calculation is on the server end.
             */
            originPrice: {{ number_format($thePrice,2) }}, // 产品价格, 这个值是不变的, 用来在option导致价格刷新的时候计算方便
            specialPrice: {{ $theSpecialPrice ? number_format($theSpecialPrice,2) : 0 }}, // 产品价格, 这个值是不变的, 用来在option导致价格刷新的时候计算方便
            originPriceDisplay: 0, // 显示用的价格属性
            specialPriceDisplay: 0, // 显示用的特价属性
            totalExtraCost: {},
            // 和产品的颜色相关
            productColours:{!! count($product_colours)>0 ? json_encode($product_colours) : '[]' !!},
            selectedColour:null,
            /*
             验证规则
             */
            rules:{
                not_null: '{{ trans('validation_rules.not_null') }}'
            }
        },
        created: function(){
            // 计算一下价格, 因为有可能option 有默认的
            this._refreshPrice();
        },
        mounted: function(){
            // 为了页面显示可以美观
            $('.hidden-first').removeClass('hidden-first');
        },
        watch:{
            'selectedColour': function(newColour, oldColour){
                this._refreshPrice();
                this._reloadProductImagesIfPossible();
            }
        },
        methods: {
            addToCartAction: function (e) {
                e.preventDefault();
                var that = this;
                if(this._buildColourItem() && this._buildOrderItem() && !this._hasError()){
                    axios.post(
                            '/products/add_to_cart',
                            {items: this.orderItem, colour: this.selectedColour}
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            // 成功 刷新购物车的count
                            if(that.redirectToCartIfAddToCartSuccess){
                                window.location.href = '/view_cart';
                            }else{
                                window._notify(that, 'success','DONE!','Add to Cart Successfully!');
                                $('#global-shopping-cart-count').text(res.data.data.count);
                                $('#global-shopping-cart-total').text(res.data.data.total + '(GST Incl.)');
                                $('#shortcut-checkout-btn').removeClass('is-invisible');
                            }
                        }else{
                            // 失败
                            window._notify(that,'error','Error','Can Not Add to Cart, please try later!');
                        }
                    });
                }
            },
            _buildColourItem: function(){
                // Check Colour option
                if(this.productColours.length == 0){
                    // No any colors
                    return true;
                }else{
                    if(!this.selectedColour){
                        // Has colours but not select
                        _notify(this, 'error', 'Message', 'Please choose the colour you like, thanks!');
                        return false;
                    }else{
                        return true;
                    }
                }
            },
            _reloadProductImagesIfPossible: function(){
                // 根据选定的颜色, 尝试去更新一下产品的图片
                console.log('refresh images');
            },
            /**
             * 创建订单项的真正方法
             */
            _buildOrderItem: function () {
                var inputs = $('#add-to-cart-form').find(':input');
                var that = this;

                this.orderItem = [];
                inputs.each(function(idx,input){
                    // Assume All good
                    var allGood = true;

                    /**
                     * 获取当前的 jQuery 对象
                     * Get Current jQuery object
                     */
                    var currentInput = $(this);

                    /**
                     * 获取当前元素的ID
                     * Get current element ID
                     */
                    var id = currentInput.attr('id');

                    // 首先进行验证,所有的产品Option的值都不可以为空
                    if(currentInput.val().trim().length == 0){
                        that._errorHandler(id, that.rules.not_null);
                        allGood = false;
                    }else{
                        var item = {};
                        if(currentInput.attr('name') == 'quantity' || currentInput.attr('type') == 'hidden'){
                            // 产品的购买数量和其他的隐藏属性都是一般的数据
                            item.type = 'general';
                            item.name = currentInput.attr('name');
                            item.value = currentInput.val();
                        }else if(currentInput.attr('type') == 'radio'){
                            // Radio 单选项, 只有被选中的会被提交
                            if(currentInput.prop('checked')){
                                item.type = 'option';
                                item.index = currentInput.data('value'); // 即产品option的对应ID
                                item.value = currentInput.val();         // 本次option的值
                            }
                        }else{
                            item.type = 'option';
                            item.index = currentInput.data('value'); // 即产品option的对应ID
                            item.value = currentInput.val();         // 本次option的值
                        }
                        if(item.type)
                            that.orderItem.push(item);
                    }

                    if(allGood){
                        that._validateHandler(id);
                    }
                });
                return true;
            },
            _errorHandler: function(id, msg){
                // 如果出现错误, 那么根据传入的input的ID, 显示错误信息
                if(id && id != 'add-to-cart-btn' && id != 'send-enquiry-for-shopping-btn'){
                    $('#'+id).addClass(this.invalidClassName+' '+this.specialInvalidClassName);
                    $('#invalid-feedback-'+id).text(msg);
                }
            },
            _validateHandler: function(id){
                // 验证通过的处理方法
                if(id && id != 'add-to-cart-btn' && id != 'send-enquiry-for-shopping-btn') {
                    $('#' + id).removeClass(this.invalidClassName);
                    $('#' + id).removeClass(this.specialInvalidClassName);
                    $('#invalid-feedback-' + id).text('');
                }
            },
            _hasError: function(){
                // 检查是否有
                return $('.' + this.specialInvalidClassName).length > 0;
            },
            /**
             * 当option项目被点击, 尝试更新价格
             */
            optionClickedHandler: function(optionId, extraValue){
                this.totalExtraCost[optionId] = extraValue;
                this._refreshPrice();
            },
            /**
             * 处理下拉列表的点击
             */
            dropDownClickedHandler: function(event, optionId){
                var select = event.target;
                var extraValue = select.options[select.selectedIndex].dataset.value;
                this.totalExtraCost[optionId] = extraValue;
                this._refreshPrice();
            },
            /**
             * 刷新当前的显示价格
             */
            _refreshPrice: function(){
                var that = this;
                this.originPriceDisplay = this.originPrice;
                this.specialPriceDisplay = this.specialPrice;
                _.each(this.totalExtraCost, function(extraValue){
                    extraValue = parseFloat(extraValue);
                    that.originPriceDisplay += extraValue;
                    that.specialPriceDisplay += extraValue;
                });
                if(this.selectedColour){
                    this.originPriceDisplay += this.selectedColour.extra_money;
                    this.specialPriceDisplay += this.selectedColour.extra_money;
                }
            },
            formatPriceText: function(priceNumber){
                return ''+priceNumber.toFixed(2);
            }
        }
    });
</script>