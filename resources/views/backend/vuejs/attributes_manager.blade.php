<script type="application/javascript">
    var AttributeSetManager = new Vue({
        el: '#attributes-manager-app',
        data: {
            currentTab:'{{ $productAttributeId ? $productAttributeId : null }}',
            dialogVisible: false,
            savingAttribute: false,
            productAttribute:{
                id: {{ $productAttributeId ? $productAttributeId : 'null' }},
                product_attribute_set_id: {{ $attributeSet->id }},
                name: '{{ $productAttribute->name ? $productAttribute->name : null }}',
                default_value: '{!! $productAttribute->default_value ? $productAttribute->default_value : null !!}',
                type: '{{ $productAttribute->type ? $productAttribute->type : 1 }}',
                location: '{{ $productAttribute->location ? $productAttribute->location : 1 }}',
                position: {{ $productAttribute->position ? $productAttribute->position : 0 }}
            },
            rules: {
                name: [
                    { required: true, message: 'Attribute Name Required', trigger: 'blur' }
                ]
            }
        },
        created: function(){

        },
        methods: {
            changeTab: function(productAttributeId){
                var that = this;
                this.currentTab = productAttributeId;
                axios.post(
                    '/api/product-attributes/load',
                    {id: productAttributeId}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that.productAttribute = res.data.msg;
                        that.productAttribute.type = that.productAttribute.type + '';
                        that.productAttribute.location = that.productAttribute.location + '';
                        that._notify('success','DONE!',that.productAttribute.name + ' is loaded!');
                    }else{
                        // 失败
                        alert('Something wrong, please refresh the page!');
                    }
                });
            },
            createNewAttribute: function(){
                this.productAttribute.product_attribute_set_id = {{ $attributeSet->id }};
                this.productAttribute.id = null;
                this.productAttribute.name = '';
                this.productAttribute.default_value = '';
                this.productAttribute.type = '1';
                this.productAttribute.location = '1';
                this.productAttribute.position = 0;
                this.currentTab = '';
            },
            saveAttribute: function(formName){
                // 验证并保存当前正在编辑的属性信息
                var that = this;
                this.$refs[formName].validate(
                    function(valid){
                        if (valid) {
                            that.savingAttribute = true;
                            that._saveAttribute();
                        }
                    }
                );
            },
            _saveAttribute: function(){
                var that = this;
                axios.post(
                        '/api/product-attributes/save',
                        {productAttribute: this.productAttribute}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        window.location.href = '/backend/attribute-sets/listing/{{ $attributeSet->id }}/' + res.data.msg;
                    }else{
                        // 失败
                        alert('Something wrong, please refresh the page!');
                    }
                    that.savingAttribute = false;
                });
            },
            deleteCurrentProductAttribute: function(){
                if(this.productAttribute.id){
                    window.location.href = '/backend/attribute-sets/delete-product-attribute/' + this.productAttribute.id;
                }
            },
            goBack: function(){
                window.location.href = '/backend/attribute-sets';
            },
            _notify: function(type, title, msg){
                // 显示弹出消息的方法
                this.$notify({title:title,message:msg,type:type,duration:1000});
                return;
            }
        }
    });
</script>