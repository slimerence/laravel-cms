<script>
    var CartViewManager = new Vue({
        el: '#cart-view-manager-app',
        data:{
            cartData: {!! json_encode($cartData) !!},
            cartTotalText: 0,
            // 为了实现方便的在本页面更新产品的数量, 添加了一个保存数量的数组
            qtys:[],
            fullscreenLoading: false
        },
        created: function(){
//            console.log(this.cartData);
            this.calculateTotal();
        },
        methods: {
            calculateTotal: function(){
                this.cartTotalText = 0;
                var that = this;
                _.forEach(this.cartData, function(item){
                    that.qtys.push(parseInt(item.qty));
                    that.cartTotalText += that._calcSubTotal(item);
                });
                this.cartTotalText = '{{ config('system.CURRENCY') }} ' + (this.cartTotalText.toFixed(2));
            },
            updateQuantity: function(idx){
                // 更新某个数量的时候, 更新订单总价
                this.cartData[idx]['qty'] = this.qtys[idx];
                this.calculateTotal();
                this._updateCartTotalInHeader();
            },
            /**
             * 删除购物车中的项目
             */
            handleDelete: function(idx, row){
                var that = this;
                this.$confirm(
                    'Are you sure to remove "'+row.name + '" from shopping cart?',
                    'Notes',
                    {type: 'warning'}
                ).then(function(){
                    that.fullscreenLoading = true;
                    // 直接去服务器更新数据
                    axios.post(
                        '/cart/remove',
                        {rowId: row.rowId}
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            // 成功从服务器删除, 那么开始本地删除
                            that.cartData.splice(idx, 1);
                            that.qtys.splice(idx, 1); // 临时保存quantity的数组对应元素要要删除, 重要

                            window._notify(that, 'success','DONE!','Item is removed!');
                            that.calculateTotal();
                            // 购物车项目数目数据减去1
                            var countCartText = $('#global-shopping-cart-count').text();
                            countCartText = parseInt(countCartText);
                            if(countCartText>0){
                                $('#global-shopping-cart-count').text(countCartText-1);
                            }
                            that._updateCartTotalInHeader();
                        }else{
                            // 失败
                            window._notify(that,'error','Error','Can not remove item, please try later!');
                        }
                        that.fullscreenLoading = false;
                    });
                });

            },
            checkout: function(){
                if(this.cartData.length == 0 || (this.cartTotalText == '{{ config('system.CURRENCY') }} '+'0.00')){
                    window._notify(this, 'error', 'Sorry!', 'Your cart is empty!');
                }else{
                    var that = this;
                    this.fullscreenLoading = true;
                    axios.post(
                        '/checkout',
                        {cart: this.cartData}
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            // 成功提交, 跳转至结账页
                            window.location.href = res.data.msg;
                        }else{
                            // 失败
                            window._notify(that,'error','Error',res.data.msg);
                        }
                        that.fullscreenLoading = false;
                    });
                }
            },
            _updateCartTotalInHeader: function(){
                // 更新购物车的总金额, 菜单部分的span
                if($('#global-shopping-cart-total').length > 0){
                    $('#global-shopping-cart-total').text(this.cartTotalText + ' (GST Incl.)');
                }
            },
            _calcSubTotal: function(item){
                var subTotal = 0;
//                if(item.options.colour && item.options.colour.extra_money>0){
//                    // 对特殊的颜色的处理
//                    subTotal += parseInt(item.qty) * item.options.colour.extra_money;
//                }
                subTotal += parseInt(item.qty) * item.price;
                return subTotal;
            },
            _convertToCurrency: function(val){
                var newVal = parseFloat(val);
                return '{{ config('system.CURRENCY') }}' + newVal.toFixed(2);
            }
        }
    });
</script>