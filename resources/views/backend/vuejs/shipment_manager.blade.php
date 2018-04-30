<script type="application/javascript">
    var ShipmentFormManager = new Vue({
        el:'#delivery-fee-form-app',
        data:{
            formData:{
                country:'{{ $deliveryFee->country }}',
                state:'',
                postcode:'',
                basic:'',
                price_per_kg:'',
                min_order_total:'',
                status: {{ $deliveryFee->status ? $deliveryFee->status : 0 }},
                target_customer_group:{{ \App\Models\Utils\UserGroup::$GENERAL_CUSTOMER }}
            },
            countries: <?php echo json_encode($countries); ?>
        },
        created(){

        },
        methods:{
            querySearch(queryString, cb) {
                var countries = this.countries;
                var results = queryString ? countries.filter(this.createFilter(queryString)) : countries;
                // 调用 callback 返回建议列表的数据
                cb(results);
            },
            createFilter(queryString) {
                return function(country) {
                    return (country.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
                };
            },
            handleSelect(item) {
                console.log(item);
            }
        }
    });
</script>