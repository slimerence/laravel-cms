<template>
    <div class="stripe-payment-component-wrap">
        <div id="card-element" class="field"></div>
        <p class="card-errors-message" v-show="cardErrors">{{ cardErrors }}</p>
    </div>
</template>

<script>
    import StripeStore from './stripe-store';
    import {StripeStyle} from './stripe-styles';
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
            mode:{
                type: String, default: 'test'
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
                cardErrors: null
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
                    this.card.on('change', ({error}) => {
                        that.cardErrors = error ? error.message : null;
                        // Re-enable the Pay button.
                        // submitButton.disabled = false;
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