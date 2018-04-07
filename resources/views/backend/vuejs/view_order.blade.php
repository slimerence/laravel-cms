<script>
    var MyOrdersApp = new Vue({
        el: '#view-orders-manager-app',
        data: {
            uid:'{{ session('user_data.uuid') }}'
        },
        created: function(){

        },
        methods: {
            issueInvoice: function(id){
                // 给客户开Invoice
                var that = this;
                axios.get('/backend/orders/ajax_issue_invoice/'+id)
                    .then(function(res){
                        if(res.data.error_no == 100){
                            that.$notify(
                                    {
                                        title:'Done',
                                        message:'Invoice Issued!',
                                        type:'success'
                                    }
                            );
                            window.location.href = '/backend/orders/view/' + id;
                        }else{
                            that.$notify(
                                {
                                    title:'Oops',
                                    message:'System is busy, please try again!',
                                    type:'error'
                                }
                            );
                        }
                    });
            },
            issueShipped: function(id){
                console.log(id);
            },
            complete: function(id){
                console.log(id);
            }
        }
    });
</script>