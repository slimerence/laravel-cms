<template>
    <div class="stripe-payment-component-wrap">
        <div id="card-element" class="field"></div>
        <p class="card-errors-message" v-show="cardErrors">{{ cardErrors }}</p>
    </div>
</template>

<script>
  /**
   * 保存Stripe相关的配置信息的类. https://github.com/stripe/stripe-payments-demo
   */
  import StripeStore from './stripe-store';
  /**
   * 控制信用卡表单显示外观. https://github.com/stripe/stripe-payments-demo
   */
  import {StripeStyle} from './stripe-styles';

  /**
   * 使用cardElement作为锚点渲染信用卡输入组件
   * @type {string}
   */
  const cardElement = 'card-element';

    export default {
        name: "stripe-payment",
        props:{
            country:{
                type: String, default: 'AU'
            },
            currency:{
                type: String, default: 'aud'
            },
            stripeCountry:{
                type: String, default: 'AU'
            },
            stripePublishableKey:{
                type: String, default: 'pk_test_PInFiPUnGR6pzLYZ2IE6oyPf'
            },
            // 是测试模式 test, 还是生产模式. 任何非 test 的值, 都会作为生产模式
            mode:{
                type: String, default: 'test'
            },
            resultTokenInputId:{
                type: String, required: true
            },
            orderFormId:{
                type: String, required: true
            }
        },
        data() {
            return {
                stripeStore: null,
                stripe: null,
                elements: null,
                card: null,
                submitButton: null,
                form: null,
                cardErrors: null,
                // Stripe 获取成功之后得到的Token
                resultToken: null
            }
        },
        mounted() {
            // 初始化 Store
            this.stripeStore = new StripeStore(
                this.mode,
                this.country,
                this.currency,
                this.stripeCountry,
                this.stripePublishableKey
            );
            // 定位 order form 和提交按钮
            this.form = document.getElementById(this.orderFormId);
            this.submitButton = this.form.querySelector('button[type=submit]');
            // 定位 form中的储存stripe token的input
            this.resultToken = document.getElementById(this.resultTokenInputId);

            // 初始化 Stripe
            this._initStripe();
        },
        methods: {
            _initStripe: function(){
                this.stripe = Stripe ? Stripe(this.stripePublishableKey) : null;
                // 初始化 Card
                if(this.stripe){
                    this.elements = this.stripe.elements();
                    this.card = this.elements.create('card', {style:StripeStyle});
                    this.card.mount('#'+cardElement);
                    // 开始输入监听
                    let that = this;
                    // 监听输入错误
                    this.card.on('change', ({error}) => {
                        that.cardErrors = error ? error.message : null;
                    });
                    // 监听提交表单
                    this.submitButton.addEventListener('click',function(event){
                        event.preventDefault();
                        that.stripe.createToken(that.card).then(function(result){
                            if (result.error) {
                                // Inform the user if there was an error.
                                that.cardErrors = result.error.message;
                            } else {
                                // Send the token to your server.
                                that.resultToken.value = result.token.id;
                                that.form.submit();
                            }
                        });
                    });
                }
            }
        }
    }
</script>

<style scoped lang="scss" rel="stylesheet/scss">
.stripe-payment-component-wrap{
    display: block;
    background: white;
    padding: 20px;
    margin-top: 10px;
    margin-bottom: -9px;
    .card-errors-message{
        color: red;
        font-size: 14px;
        line-height: 26px;
    }
}
</style>