<script>
    var PlaceOrderCheckoutApp = new Vue({
        el:'#place-order-checkout-app',
        data: {
            customer:'{{ isset($user) && $user ? $user->uuid : null }}',
            submitFormInProgress: false,
            shippingForm:{
                name:null,
                email:null,
                phone:null,
                address:null,
                city:null,
                state:'VIC',
                postcode:null
            },
            rules: {
                name: [
                    { required: true, message: 'Name is required', trigger: 'blur' }
                ],
                email: [
                    { required: true, message: 'Name is required', trigger: 'blur' }
                ],
                phone: [
                    { required: true, message: 'Phone is required', trigger: 'blur' }
                ],
                address: [
                    { required: true, message: 'Address is required', trigger: 'blur' }
                ],
                city: [
                    { required: true, message: 'Suburb is required', trigger: 'blur' }
                ],
                postcode: [
                    { required: true, message: 'Postcode is required', trigger: 'blur' }
                ]
            }
        },
        created(){

        },
        methods:{
            submitForm: function(formName){
                var that = this;
                this.$refs[formName].validate(function(valid){
                    if(valid){
                        // 验证成功, 提交到服务器
                        that.submitFormInProgress = true;
                        axios.post(
                            '/frontend/customer/quick-checkout-register',
                            {shippingForm:that.shippingForm}
                        ).then(function(res){
                            that.submitFormInProgress = false;
                            if(res.data.error_no == {{ \App\Models\Utils\JsonBuilder::CODE_SUCCESS }}){
                                that.customer = res.data.data.uuid;
                                window._notify(that,'success','Done','All Good, please submit your order now!');
                            }else{
                                window._notify(that,'error','Error',res.data.data.errorMsg);
                                if(res.data.data.errorCode == {{ \App\User::ERROR_CODE_EMAIL_UNIQUE }}){
                                    // Email exists

                                }
                            }
                        });
                    }else{
                        // 验证失败
                        window._notify(that,'error','Error','Please fill the form!');
                    }
                });
            }
        }
    });
</script>