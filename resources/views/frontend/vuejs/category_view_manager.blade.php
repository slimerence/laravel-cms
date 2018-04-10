<script type="application/javascript">
var categoryViewManager = new Vue({
    el:"#category-view-manager",
    data: {
        user:'{{ session('user_data.uuid') }}',
        showSendEnquiryForm: false,
        showAddToCartForm: false,
        formLabelWidth: '120px',
        userIsLocated: false,
        enquiryForm:{
            selectedProductName:'',
            selectedProductId: '',
            name: '{{ session('user_data.name') }}',
            email: '{{ session('user_data.email') }}',
            phone: '',
            message: ''
        },
        rules:{
            name:[
                { required: true, message: 'Name is required', trigger: 'blur' }
            ],
            email:[
                { required: true, message: 'Email is required', trigger: 'blur' }
            ],
            phone:[
                { required: true, message: 'Phone is required', trigger: 'blur' }
            ]
        }
    },
    created: function(){
        this.userIsLocated = this.user.length > 0;
    },
    methods:{
        cancelEnquiry: function(){
            this.showSendEnquiryForm = false;
            this.enquiryForm.selectedProductName = '';
            this.enquiryForm.message = '';
        },
        sendEnquiryAction: function(formName){
            var that = this;
            this.$refs[formName].validate(function(valid) {
                if (valid) {
                    that.showSendEnquiryForm = false;
                    that.enquiryForm.user = that.user;
                    axios.post(
                        '/contact-us',
                        {lead:that.enquiryForm}
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            that.$notify({
                                title: 'Success',
                                message: 'Your enquiry has been saved, we will contact you very soon!',
                                type: 'success',
                                position: 'bottom-right'
                            });
                        }else{
                            that.$notify.error({
                                title: 'Error',
                                message: 'System is busy, please try again later!',
                                position: 'bottom-right'
                            });
                        }
                    });
                } else {
                    return false;
                }
            });
        },
        startEnquiry: function(productName,id){
            this.showSendEnquiryForm = true;
            this.enquiryForm.selectedProductName = productName;
            this.enquiryForm.selectedProductId = id;
        }
    }
});
</script>