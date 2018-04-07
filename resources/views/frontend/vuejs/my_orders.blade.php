<script>
var MyOrdersApp = new Vue({
    el: '#my-orders-manager-app',
    data: {
        id: '{{ session('user_data.uuid') }}'
    },
    created: function(){

    },
    methods: {
        /**
         * 删除之前问一下原因
         */
        askWhy: function(e){
            e.preventDefault();
            var action = e.target.getAttribute('href');
            var that = this;
            window._questionBox(
                    this,
                    e.target.dataset.msg,
                    'Please Tell Us why?',
                    function(data){
                        if(data.trim().length == 0){
                            // 没有留下原因, 直接删除
                            data = 'N/A';
                        }
                        // 直接删除
                        axios.post(
                                action,
                                {note: data}
                        ).then(function(res){
                            if(res.data.error_no == 100){
                                // Declined 成功
                                window._notify(that, 'success','DONE!','Order Declined Successfully!');
                                setTimeout(function(){
                                    window.location.href = '/frontend/my_orders/' + that.id;
                                },800);
                            }else{
                                // Declined Failed
                                window._notify(that, 'error','Oops!',res.data.msg);
                            }
                        });
                    },
                    'Decline Action Cancelled!'
            );
        }
    }
});
</script>