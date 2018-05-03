<script type="application/javascript">
    var BrandsMangerApp = new Vue({
        el: '#brands-manager-app',
        data: {
            serialForm:{
                id:null,
                brand_id:null,
                name:'',
                keyword:'',
                selectedBrandName:''
            },
            isDeleting: false,
            isSaving: false,
            serialFormVisible: false,
            formLabelWidth: '120px',
            pageNumber: {{ $brands->currentPage() }}
        },
        created(){

        },
        methods: {
            showSerialForm: function(brandId, brandName, e){
                e.preventDefault();
                this.serialForm.brand_id = brandId;
                this.serialForm.id = null;
                this.serialForm.name = '';
                this.serialForm.keyword = '';
                this.serialForm.selectedBrandName = brandName;
                this.serialFormVisible = true;
            },
            editSerialForm: function(serialId){
                // 加载serial的数据
                var that = this;
                axios.get('/api/brands/get-serial?id='+serialId)
                        .then(function(res){
                            if(res.data.error_no == 100){
                                that.serialForm.id = serialId;
                                that.serialForm.brand_id = res.data.data.serial.brand_id;
                                that.serialForm.name = res.data.data.serial.name;
                                that.serialForm.keyword = res.data.data.serial.keyword;
                                that.serialForm.selectedBrandName = res.data.data.brand_name;
                                that.serialFormVisible = true;
                            }
                        });
            },
            saveSerial: function(){
                // 保存
                if(this.serialForm.name.length>0 && this.serialForm.brand_id){
                    // 验证成功
                    var that = this;
                    this.isSaving = true;
                    axios.post('/api/brands/save-serial',{serialForm:this.serialForm})
                        .then(function(res){
                            if(res.data.error_no == 100){
                                window.location.href = '/backend/brands?page=' + that.pageNumber;
                            }
                            that.isSaving = false;
                            that.serialFormVisible = false;
                        });
                }
            },
            deleteSerial: function(){
                // 删除
                if(this.serialForm.id){
                    this.isDeleting = true;
                    var that = this;
                    axios.get('/api/brands/delete-serial?id=' + this.serialForm.id)
                        .then(function(res){
                            if(res.data.error_no == 100){
                                window.location.href = '/backend/brands?page=' + that.pageNumber;
                            }
                            that.isDeleting = false;
                            that.serialFormVisible = false;
                        });
                }
            }
        }
    });
</script>