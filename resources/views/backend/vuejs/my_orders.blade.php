<script>
var MyOrdersApp = new Vue({
    el: '#my-orders-manager-app',
    data: {
        orderKeyword: '',
        orders:[]
    },
    created: function(){

    },
    methods: {
        orderSearchAsync: function(queryString, callback){
            if(queryString.trim().length>1){
                var that = this;
                axios.post(
                    '/backend/orders/ajax_search',
                    {key: queryString.trim()}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        callback(res.data.data);
                    }
                });
            }
        },
        handleResultSelect: function(item){
            window.location.href = '/backend/orders/view/' + item.id;
        }
    }
});
</script>