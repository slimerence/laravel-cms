<script type="application/javascript">
    var RelatedProductsManager = new Vue({
        el: '#related-products-manager',
        data: {
            tableData: {!! json_encode($otherProducts) !!},
            multipleSelection: [],
            saving: false,
            productId: '{{ $product->id }}'
        },
        created: function(){
        },
        mounted: function(){
            // 勾选已经关联的产品行
            var that = this;
            var selected = {!! json_encode($existedRelatedProducts) !!};
            var existed = [];
            selected.forEach(function(row){
                var idx = that._getRowIndex(row.id);
                if(idx !== null){
                    console.log(555);
                    existed.push(that.tableData[idx]);
                }
            });
            setTimeout(function(){
                that.toggleSelection(existed);
            },500);
        },
        methods:{
            selectAll: function(){
                this.toggleSelection(this.tableData);
            },
            toggleSelection: function(rows) {
                var that = this;
                if (rows) {
                    rows.forEach(function(row){
                        that.$refs.multipleTable.toggleRowSelection(row);
                    });
                } else {
                    this.$refs.multipleTable.clearSelection();
                }
            },
            handleSelectionChange: function(val){
                this.multipleSelection = val;
            },
            _getRowIndex: function(id){
                var idx = null;
                this.tableData.forEach(function(row, index){
                    if(row.id == id){
                        idx = index;
                    }
                });
                return idx;
            },
            save: function(){
                this.saving = true;
                var that = this;
                axios.post(
                    '/backend/products/save-related-products',
                    {productId: this.productId, selected: this.multipleSelection}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        that.$notify.success({
                            title:'Success',
                            message: 'Action is completed successfully!'
                        });
                    }else{
                        that.$notify.error({
                            title:'Error',
                            message: 'System is busy, please try later!'
                        });
                    }
                    that.saving = false;
                }).catch(function(error){
                    that.saving = false;
                });
            }
        }
    });
</script>