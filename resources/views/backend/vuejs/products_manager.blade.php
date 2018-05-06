<script>
    var ProductManager = new Vue({
        el: '#products-manager-app',
        data: {
            dialogVisible: false,
            savingProduct: false,
            cloningProduct: false,  // 指示是否正在克隆产品
            currentTab: 'basic',
            // 产品的颜色
            showProductColourForm: false,
            productColourForm:{
                index: false,
                id:null,
                product_id:null,
                name:'',
                type:'{{ \App\Models\Utils\ColourTool::$TYPE_TEXT }}',
                extra_money:0,
                value:'',
                imageUrl: null // 如果是选择了图片类型, 那么成功上传之后的url保存在这里
            },
            productColours:[],
            // 产品属性附件上传
            fileAttachmentList:[],
            // 产品图片上传
            productDialogVisible: false,
            productImageUrl: '',
            categories: {{ $product->id ? json_encode($product->getCategoriesId()) : '[]' }},
            productImages: [],
            // 产品的额外信息
            editExistProductOption: false,  // 表示是否处于产品option编辑的模式
            idOfEditingProductOptionIndex: null, // 保存正在被编辑的产品option的索引值
            hideProductOptionForm: true,
            hideProductOptionItemForm: true,
            productOptionForm:{
                id: null,
                name:'',
                type: {{ \App\Models\Utils\OptionTool::$TYPE_TEXT }},
                items:[]
            },
            productOptionItemForm:{
                id: null,
                label:'',
                extra_value:''
            },
            currentPreparedOptionIndexToDelete: null, // 临时保存准备要删除的product option 的index
            dialogDeleteProductOptionVisible: false,  // 控制删除product option的确认框的显示
            productOptions: [], // 当前产品的options
            // 产品额外信息结束
            currentAttributeSetId: {{ $product->attribute_set_id ? $product->attribute_set_id : \App\Models\Utils\ProductType::$BASIC_PRODUCT_ATTRIBUTE_SET }},
            productAttributes: [],
            productAttributesValues: [],
            product: {
                id: {{ $product->id ? $product->id : 'null' }},
                name: '<?php echo $product->name; ?>',
                type: {{ $product->type ? $product->type : \App\Models\Utils\ProductType::$GENERAL_ITEM }},
                group_id: {{ $product->group_id ? $product->group_id : 0 }},  // 特殊客户组的 ID
                attribute_set_id: {{ $product->attribute_set_id ? $product->attribute_set_id : \App\Models\Utils\ProductType::$BASIC_PRODUCT_ATTRIBUTE_SET }},  // 产品所属的属性集合ID
                sku: '<?php echo $product->sku; ?>',
                uri: '<?php echo $product->uri; ?>',
                position: '<?php echo $product->position; ?>',
                short_description: '<?php echo str_replace(PHP_EOL,'', $product->short_description); ?>',
                description: '<?php echo str_replace(PHP_EOL,'', $product->description); ?>',
                keywords: '<?php echo $product->keywords; ?>',
                seo_description: '<?php echo $product->seo_description; ?>',
                default_price:'<?php echo $product->default_price; ?>',
                special_price:'<?php echo $product->special_price; ?>',
                tax:'<?php echo $product->tax ? $product->tax : 10; ?>',
                min_quantity:'<?php echo $product->min_quantity ? $product->min_quantity : 1; ?>',
                manage_stock: '{{ $product->manage_stock ? 1 : 0 }}',
                stock: '<?php echo $product->stock ? $product->stock : 0; ?>',
                unit_text: '<?php echo $product->unit_text; ?>',
                brand: '<?php echo $product->brand; ?>',
                brand_serial_id: <?php echo $product->brand_serial_id ? $product->brand_serial_id : 'null'; ?>,
                serial_name: '<?php echo $product->serial_name; ?>',
                is_group_product: <?php echo $product->is_group_product ? 'true' : 'false'; ?>,
                is_configurable_product: <?php echo $product->is_configurable_product ? 'true' : 'false'; ?>
            },
            rules: {
                name: [
                    { required: true, message: 'Product Name Required', trigger: 'blur' }
                ],
                sku: [
                    { required: true, message: 'Product SKU Required', trigger: 'blur' }
                ],
                stock: [
                    { required: true, message: 'Stock Number Required', trigger: 'blur' }
                ],
                min_quantity: [
                    { required: true, message: 'Minimum Quantity Required', trigger: 'blur' }
                ],
                default_price: [
                    { required: true, message: 'Basic Price Required', trigger: 'blur' }
                ],
                position: [
                    { required: true, message: 'Product\'s position is Required', trigger: 'blur' }
                ]
            },
            brands: {!! json_encode($brands) !!},
            brandSerials: [],   // 所属品牌的Serial列表
            selectedBrandSerialId: <?php echo $product->brand_serial_id ? $product->brand_serial_id : 'null'; ?>,   // 当前选择的 serial id
            currentBrandImage: null,
            currentBrand: null,
            // 搜索GroupProduct的关键字
            groupProductSearchKeyword: '',
            groupProductsCandidates: [],
            existedGroupProducts:[]     // 已经被确定成为一组的products
        },
        created: function(){
            if(this.product.id){
                // 表示是编辑一个已经存在的产品 icarautomotive.com.au
                this._loadProductImages();
                this._loadProductExistOptionsAndColours();
                this._loadCurrentBrandData(this.product.brand);
            }
            $('#products-manager-app').removeClass('invisible');
            // 加载当前属性集的属性
            this._loadProductAttributes();
        },
        watch: {
            currentAttributeSetId: function (val) {
                // 观察产品所归属的属性集的变化
                this.product.attribute_set_id = val;
                this._loadProductAttributes();
                this.productAttributesValues = [];
            },
            'product.name': function(val){
                if(this.product.id === null){
                    // 只有在新添产品的时候才自动生成sku和uri
                    this.product.sku = val;
                    this.product.uri = _replace_space_with_dash(val);
                    // 自动填入seo keywords
                    this.product.keywords = val;
                    this.product.seo_description = val;
                }
            }
        },
        methods: {
            // 组合产品相关的方法
            _loadGroupedProducts: function(){
                // 加载本产品的产品组合
                if(this.product.is_group_product){
                    // 如果是组合产品
                }
            },
            switchOnAddGroupProductForm: function(){
                // 打开组合产品输入表单
                if(!this.product.id && this.product.is_group_product === true){
                    this.product.is_group_product = false;
                    this.$message.error('请您先保存当前的产品, 然后才能继续添加产品组合!');
                }
            },
            fetchRemoteProducts: function(queryString, cb){
                if(queryString.trim().length > 2){
                    var that = this;
                    axios.post(
                        '/api/products/ajax_search_for_group',
                        {key: queryString.trim(), excludes:[this.product.id]}
                    ).then(function(res){
                        console.log(res)
                    });
                }
            },
            handleGroupProductSelected: function(item){

            },
            // 组合产品相关的方法 结束
            // 产品品牌相关
            brandSearch: function(queryString, cb){
                var brandsArray = this.brands;
                var results = queryString ? brandsArray.filter(this._createBrandFilter(queryString)) : brandsArray;
                // 调用 callback 返回建议列表的数据
                cb(results);
            },
            _createBrandFilter: function(queryString){
                return function(brand){
                    return (brand.value.toLowerCase().indexOf(queryString.toLowerCase()) !== -1);
                };
            },
            handleSelectBrand: function(item){
                this.product.brand = item.value;
                this._loadCurrentBrandData(item.value);
            },
            _loadCurrentBrandData: function(name){
                if(name && name.trim().length > 0){
                    // 品牌名称如果不为空, 则尝试重复加载
                    var that = this;
                    axios.post(
                            '/api/brands/load-by-name',
                            {name:name}
                    ).then(function(res){
                        if(res.data.error_no === 100){
                            that.currentBrandImage = res.data.data.brandImage;
                            that.currentBrand = res.data.data.brand;
                            that.brandSerials = res.data.data.brandSerials;
                        }
                    });
                }
            },
            // 产品品牌结束
            // 产品品牌Serial 选择的回调开始
            brandSerialChanged: function(selectedId){
                // 根据选定的 id, 填充进产品对象
                var that = this;
                _.each(this.brandSerials, function(item){
                    if(item.id == selectedId){
                        that.product.brand_serial_id = item.id;
                        that.product.serial_name = item.name;
                    }
                });
            },
            // 产品品牌Serial 选择的回调结束
            // 产品的颜色相关
            addNewProductColour: function(){
                this._resetProductColourForm();
                this.showProductColourForm = true;
            },
            saveProductColour: function(){
                if(this.productColourForm.name.trim().length == 0){
                    // 产品颜色必须有个名字
                    this.$message.error('请给本颜色指定一个名字!');
                    return false;
                }

                var color = {};
                color.index = this.productColourForm.index;
                color.name = this.productColourForm.name;
                color.id = this.productColourForm.id;
                color.type = this.productColourForm.type;
                color.value = this.productColourForm.value;
                color.product_id = this.productColourForm.product_id;
                color.extra_money = this.productColourForm.extra_money;
                color.imageUrl = this.productColourForm.imageUrl;

                if(this.productColourForm.index === false){
                    // 表示添加一个新的颜色
                    color.index = this.productColours.length === 0 ? 0 : this.productColours.length;
                    this.productColours.push(color);
                    this._resetProductColourForm();
                }else{
                    this.productColours[color.index] = color;
                    this._resetProductColourForm();
                }
                this.showProductColourForm = false;
            },
            removeGstDefault: function(){
                // 当前输入的价格值减去GST的快捷方法
                if(this.product.default_price && this.product.tax){
                    this.product.default_price = (this.product.default_price/(1+this.product.tax/100)).toFixed(2);
                }else{
                    // 默认的消费税为10
                    this.product.default_price = (this.product.default_price/1.1).toFixed(2);
                }
            },
            removeGstSpecial: function(){
                // 当前输入的价格值减去GST的快捷方法
                if(this.product.special_price && this.product.tax){
                    this.product.special_price = (this.product.special_price/(1+this.product.tax/100)).toFixed(2);
                }else{
                    // 默认的消费税为10
                    this.product.special_price = (this.product.special_price/1.1).toFixed(2);
                }
            },
            _resetProductColourForm: function(data){
                if(data){
                    // 如果给了颜色数据, 就使用
                    this.productColourForm.index = data.index;
                    this.productColourForm.name = data.name;
                    this.productColourForm.id = data.id;
                    this.productColourForm.type = data.type+'';
                    this.productColourForm.value = data.value;
                    this.productColourForm.product_id = data.product_id;
                    this.productColourForm.extra_money = data.extra_money;
                    this.productColourForm.imageUrl = data.imageUrl;
                }else{
                    this.productColourForm.index = false;
                    this.productColourForm.name = '';
                    this.productColourForm.id = null;
                    this.productColourForm.type = '{{ \App\Models\Utils\ColourTool::$TYPE_TEXT }}';
                    this.productColourForm.value = '';
                    this.productColourForm.product_id = null;
                    this.productColourForm.extra_money = 0;
                    this.productColourForm.imageUrl = '';
                }
            },
            loadExistProductColour: function(idx){
                var colour = this.productColours[idx];
                colour.index = idx;
                this._resetProductColourForm(colour);
                this.showProductColourForm = true;
            },
            deleteExistProductColour: function(idx){
                // 同样需要检查是否索要被删除的option item是否有ID, 如果有, 则去服务器删除
                var pColor = this.productColours[idx];
                var that = this;

                this.$confirm('此操作将永久删除该颜色, 是否继续?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(function(){
                        if(pColor){
                            if(pColor.id){
                                // 到服务器上删除
                                axios.post(
                                        '<?php echo url('api/products/colour/delete'); ?>',
                                        {id: pColor.id}
                                ).then(function(res){
                                    if(res.data.error_no == 100){
                                        // 成功
                                        that.productColours.splice(idx, 1);
                                        that.$message({
                                            type: 'success',
                                            message: '删除成功!'
                                        });
                                    }else{
                                        that.$message({
                                            type: 'danger',
                                            message: '删除失败, 请稍后再试!'
                                        });
                                    }
                                });
                            }else{
                                // 本地删除即可
                                that.productColours.splice(idx, 1);
                            }
                        }
                }).catch(function(){
                    that.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            handleColourPictureSuccess: function(res, file){
                // 用户选择了图片来代表颜色, 那么图片上传成功之后的回调
                this.productColourForm.imageUrl = res;
            },
            beforeColourPictureUpload: function(file){
                // 用户选择了图片来代表颜色, 那么图片上传之前的处理
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isJPG && !isPNG) {
                    this.$message.error('图片只能是 JPG 或 PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            // 产品的属性相关
            _loadProductAttributes: function(){
                // 加载当前属性集的属性, 同时会加载其父级的属性
                var that = this;
                axios.post(
                        '<?php echo url('api/product-attributes/set-to-load'); ?>',
                        {set_id: this.currentAttributeSetId, product_id: this.product.id}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that.productAttributes = res.data.data.pas;
                        that.productAttributesValues = res.data.data.avs;
                    }else{
                        // 失败
                        that._notify('info','Notice','无法加载选定属性集的数据!');
                    }
                });
            },
            _loadProductImages: function(){
                // 编辑产品时加载产品的图片
                var that = this;
                axios.post(
                    '<?php echo url('api/images/product'); ?>',
                    {id: '<?php echo $product->uuid; ?>'}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that.productImages = res.data.data;
                    }else{
                        // 失败
                        that._notify('info','Notice','产品还没有添加过图片!');
                    }
                });
            },
            _loadProductExistOptionsAndColours: function(){
                // 编辑产品时加载产品的选项
                var that = this;
                axios.post(
                        '<?php echo url('api/products/options/load'); ?>',
                        {id: '<?php echo $product->id; ?>'}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that.productOptions = res.data.data.options;
                        that.productColours = res.data.data.colours;
                    }
                });
            },
            removeExistImage: function(index){
                // 删除已经存在的图片. 这个方法直接申请服务器去删除,成功之后再本地删除
                var that = this;
                var img = this.productImages[index];
                if(img){
                    axios.post(
                            '<?php echo url('api/images/delete'); ?>',
                            {id: img.id}
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            // 成功
                            that.productImages.splice(index,1);
                            that._notify('success','Done','图片已经成功删除!');
                        }else{
                            // 失败
                            that._notify('info','Notice','无法删除图片!');
                        }
                    });
                }
            },
            cloneProduct: function(formName){
                // 将当前的产品进行Clone
                // 验证当前正在编辑的产品信息
                var that = this;
                this.$refs[formName].validate(
                        function(valid){
                            if (valid) {
                                that._cloneProduct();
                            } else {
                                that._notify('error','还有必须的字段没有填写');
                                return false;
                            }
                        }
                );
            },
            _cloneProduct: function(){
                if(this.categories.length == 0){
                    this._notify('error','请给产品至少指定一个分类');
                }else{
                    var that = this;
                    this.cloningProduct = true;
                    _.forEach(this.$refs.productImagesUploader.uploadFiles, function(theFile){
                        that.productImages.push(theFile.response);
                    });

                    // 检查是否有Product Option 也一同提交
                    var paData = [];
                    _.each(this.productAttributes, function(pa, idx){
                        var tmp = {};
                        tmp.product_attribute_id = pa.id;
                        var tmpValue = null;

                        // 使用了 redactor 编辑器, 需要做如下的处理
                        if(pa.type=={{\App\Models\Utils\OptionTool::$TYPE_RICH_TEXT}}){
                            // 表示该属性的类型为文本编辑器, 那么检查, 是否有对应的ref
                            _.each(that.$refs, function(ref,objName){
                                if(objName == 'productAttribute'+idx){
                                    // 如果ref的名字是 productAttribute0, productAttribute1 , ...
                                    var editorObject = ref[0];
                                    tmpValue = editorObject.getContent();
                                }
                            })
                        }else{
                            // 不是文本编辑器
                            if(that.productAttributesValues[idx]){
                                tmpValue = that.productAttributesValues[idx];
                            }
                        }

                        tmp.value = tmpValue;
                        paData.push(tmp);
                    });

                    // 由于使用了 vuejs-editor, 需要单独通过下面的方式获取产品的description最新值
                    this.product.description = this.$refs.productDescriptionEditor.getContent();
                    this.product.short_description = this.$refs.productShortDescriptionEditor.getContent();

                    axios.post(
                            '<?php echo url('api/products/clone') ?>',
                            {
                                product:this.product,
                                images: this.productImages,
                                categories: this.categories,
                                productOptions: this.productOptions,
                                productColours: this.productColours,
                                productAttributeData: paData // 和产品属性相关的值
                            }
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            // 成功 从新加载一下
                            that._notify('success','DONE!','Product Cloned!');
                            window.location.href = '<?php echo url('backend/products/edit'); ?>/' + res.data.msg;
                        }else{
                            // 失败
                            that._notify('error','Error','Can not clone product, please try later!');
                        }
                        that.cloningProduct = false;
                    });
                }
            },
            saveProduct: function(formName){
                // 验证并保存当前正在编辑的产品信息
                var that = this;
                this.$refs[formName].validate(
                    function(valid){
                        if (valid) {
                            that._saveProduct();
                        } else {
                            that._notify('error','还有必须的字段没有填写');
                            return false;
                        }
                    }
                );
            },
            _saveProduct: function(){
                // 保存产品数据的真正方法
                if(this.categories.length == 0){
                    this._notify('error','请给产品至少指定一个分类');
                }else{
                    var that = this;
                    this.savingProduct = true;
                    _.forEach(this.$refs.productImagesUploader.uploadFiles, function(theFile){
                        that.productImages.push(theFile.response);
                    });

                    // 检查是否有Product Option 也一同提交
                    var paData = [];
                    _.each(this.productAttributes, function(pa, idx){
                        var tmp = {};
                        tmp.product_attribute_id = pa.id;
                        var tmpValue = null;

                        // 使用了 redactor 编辑器, 需要做如下的处理
                        if(pa.type=={{\App\Models\Utils\OptionTool::$TYPE_RICH_TEXT}}){
                            // 表示该属性的类型为文本编辑器, 那么检查, 是否有对应的ref
                            _.each(that.$refs, function(ref,objName){
                                if(objName == 'productAttribute'+idx){
                                    // 如果ref的名字是 productAttribute0, productAttribute1 , ...
                                    var editorObject = ref[0];
                                    tmpValue = editorObject.getContent();
                                }
                            })
                        }else{
                            // 不是文本编辑器
                            if(that.productAttributesValues[idx]){
                                tmpValue = that.productAttributesValues[idx];
                            }
                        }

                        tmp.value = tmpValue;
                        paData.push(tmp);
                    });

                    // 由于使用了 vuejs-editor, 需要单独通过下面的方式获取产品的description最新值
                    this.product.description = this.$refs.productDescriptionEditor.getContent();
                    this.product.short_description = this.$refs.productShortDescriptionEditor.getContent();

                    axios.post(
                        '<?php echo url('api/products/save') ?>',
                        {
                            product:this.product,
                            images: this.productImages,
                            categories: this.categories,
                            productOptions: this.productOptions,
                            productColours: this.productColours,
                            productAttributeData: paData // 和产品属性相关的值
                        }
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            // 成功 从新加载一下
                            that._notify('success','DONE!','Product Saved!');
                            window.location.href = '/backend/products/edit/' + res.data.msg;
                        }else{
                            // 失败
                            that._notify('error','Error','Can not save product, please try later!');
                        }
                        that.savingProduct = false;
                    });
                }
            },
            createNewProduct: function(){
                window.location.href = '<?php echo url('backend/products/add') ?>';
            },
            backToProducts: function(){
                window.location.href = '<?php echo url('backend/products') ?>';
            },
            deleteSelectedProduct: function(){
                window.location.href = '<?php echo url('backend/products/delete/'.$product->uuid) ?>';
            },
            changeTab: function(tab){
                this.currentTab = tab;
            },
            // 产品图片上传相关
            handlePictureCardPreview: function(file){
//                this.productImageUrl = file.url;
                this.productDialogVisible = true;
            },
            putImagesUrlIntoList: function(response, file, fileList){
//                console.log(response);
//                console.log(file);
//                console.log(fileList);
            },
            handleProductImageRemove: function(file, fileList){
//                console.log(file, fileList);
            },
            _notify: function(type, title, msg){
                // 显示弹出消息的方法
                this.$notify({title:title,message:msg,type:type});
            },
            addNewProductOption: function () {
                // 向产品添加一个新的option
                this.hideProductOptionForm = false;
                this.productOptionForm.id = null;
                this.productOptionForm.items = [];
                this.productOptionForm.name = '';
                this.productOptionForm.type = '<?php echo \App\Models\Utils\OptionTool::$TYPE_TEXT; ?>';
                this.hideProductOptionItemForm = true;
            },
            addNewProductOptionItem: function () {
                // 显示新option item的表格
                this.hideProductOptionItemForm = false;
            },
            saveNewProductOptionItem: function(){
                // 保存一个新的产品 option 的 item
                var newOptionItem = {};
                newOptionItem.label = this.productOptionItemForm.label;
                newOptionItem.extra_value = this.productOptionItemForm.extra_value;
                this.productOptionForm.items.push(newOptionItem);
                this._resetProductOptionItemForm();

                // 检查一下当前选项的类型
                if(this.productOptionForm.type == '<?php echo \App\Models\Utils\OptionTool::$TYPE_TEXT; ?>'){
                    // 文本类型,那只有一个option item. 本 option 也就添加完了
                    var newOption = {};
                    newOption.name = this.productOptionForm.name;
                    newOption.type = this.productOptionForm.type;
                    newOption.items = this.productOptionForm.items;
                    this.productOptions.push(newOption);
                    this._afterOptionAdded();
                }
            },
            removeProductOptionItem: function(idx){
                // 同样需要检查是否索要被删除的option item是否有ID, 如果有, 则去服务器删除
                var oItem = this.productOptionForm.items[idx];
                if(oItem){
                    if(oItem.id){
                        // 到服务器上删除
                        var that = this;
                        axios.post(
                                '<?php echo url('api/products/option_item/delete'); ?>',
                                {id: oItem.id}
                        ).then(function(res){
                            if(res.data.error_no == 100){
                                // 成功
                                that.productOptionForm.items.splice(idx, 1);
                            }
                        });
                    }else{
                        // 本地删除即可
                        this.productOptionForm.items.splice(idx, 1);
                    }
                }
            },
            saveNewProductOptionItemComplex: function(){
                // 当为Dropdown类型的Option通过完成按钮保存时
                var newOption = {};
                newOption.name = this.productOptionForm.name;
                newOption.type = this.productOptionForm.type;
                newOption.id = this.productOptionForm.id;
                newOption.items = this.productOptionForm.items;

                // 保存复杂类型的option
                if(!this.editExistProductOption){
                    // 如果这个时候不是编辑产品 option 的情况, 则生成新的
                    this.productOptions.push(newOption);
                    this._notify('success','成功添加了新的Option');
                }else{
                    // 这个时候是在编辑产品的option, 编辑完成之后保存
                    this.productOptions[this.idOfEditingProductOptionIndex] = newOption;
                    this._notify('success','请点击 Save 按钮将修改同步到服务器, 以免丢失');
                }

                this._afterOptionAdded();
                this.editExistProductOption = false; // 关闭产品 option 编辑的模式
                this.idOfEditingProductOptionIndex = null;
            },
            loadExistProductOption: function(idx){
                // 加载已经存在的产品 option 以供编辑
                var newOption = {};
                newOption.name = this.productOptions[idx].name;
                newOption.type = this.productOptions[idx].type + ''; // 需要转换成字符串
                newOption.items = this.productOptions[idx].items;
                newOption.id = this.productOptions[idx].id;
                this.productOptionForm = newOption;

                this.hideProductOptionForm = false;
                this.hideProductOptionItemForm = false;

                this.editExistProductOption = true;
                this.idOfEditingProductOptionIndex = idx;
            },
            /**
             * 显示对话框, 询问是否需要删除给定index的产品option
             */
            prepareDeleteExistProductOption: function(pOptionIdx){
                this.dialogDeleteProductOptionVisible = true;
                this.currentPreparedOptionIndexToDelete = pOptionIdx;
            },
            /**
             * 取消继续删除产品的某个option
             */
            cancelDeleteExistProductOption: function(){
                this.dialogDeleteProductOptionVisible = false;
                this.currentPreparedOptionIndexToDelete = null;
            },
            /**
             * 真正执行删除option的方法.
             * 1: 如果设定的index的option有ID 则服务器删除
             * 2: 如果没有ID, 那么直执行本地删除即可
             */
            deleteExistProductOption: function(){
                // 删除一个产品的option. 检查一下是否已经有了ID
                var pOption = this.productOptions[this.currentPreparedOptionIndexToDelete];
                if(pOption){
                    if(pOption.id){
                        // 这个时候需要去服务器删除
                        var that = this;
                        axios.post(
                                '<?php echo url('api/products/options/delete'); ?>',
                                {id: pOption.id}
                        ).then(function(res){
                            if(res.data.error_no == 100){
                                // 成功
                                that.productOptions.splice(that.currentPreparedOptionIndexToDelete, 1);
                                that.currentPreparedOptionIndexToDelete = null;
                            }
                        });
                    }else{
                        // 新option, 直接本地删除即可
                        this.productOptions.splice(this.currentPreparedOptionIndexToDelete, 1);
                        this.currentPreparedOptionIndexToDelete = null;
                    }
                }
                this.dialogDeleteProductOptionVisible = false;
            },
            _resetProductOptionItemForm: function(){
                // 重置 option item 的独立方法
                this.productOptionItemForm.id = null;
                this.productOptionItemForm.label = '';
                this.productOptionItemForm.extra_value = '';
            },
            _afterOptionAdded: function () {
                // 当产品的 Option 添加完成之后, 重置整个的 Option 表单
                this.hideProductOptionItemForm = true;
                this.hideProductOptionForm = true;
                this.productOptionForm.name = '';
                this.productOptionForm.id = null;
                this.productOptionForm.type = '<?php echo \App\Models\Utils\OptionTool::$TYPE_TEXT; ?>';
                this.productOptionForm.items = [];
            },
            handleAttachmentPreview: function(file){
                // 负责处理产品属性为 Attachment 时的处理
//                console.log(file);
            },
            handleAttachmentRemove: function(file, fileList){
                var that = this;
                if(file.response){
                    if(file.status=='success' && file.response){
                        // 表明文件是上传成功了的, 那么就要去找到它
                        _.forEach(this.productAttributesValues[file.response.index], function(pav, index){
                            if(pav.url == file.response.path){
                                that.productAttributesValues[file.response.index][index] = null;
                            }
                        });
                    }
                }else{
                    // 这个时候表示: 由跟新产品界面已经加载的属性值的删除
                    if(file.status=='success' && file.idx){
                        // 表明文件是上传成功了的, 那么就要去找到它
                        _.forEach(this.productAttributesValues[file.idx], function(pav, index){
                            if(pav.url == file.url){
                                that.productAttributesValues[file.idx][index] = null;
                            }
                        });
                    }
                }
            },
            putAttachmentUrlIntoList: function(response, file, fileList){
                // 将当前上传的所以和取得的 attribute的值保存起来。 服务器会把 index 给传递回来
                this.productAttributesValues[response.index].push(
                    {
                        name:file.name,
                        url:response.path
                    }
                );
            },
            submitAttachment: function(idx,formName){
                // 自定义的进行文件上传的方法. 在上传之前, 先初始化指定的idx索引为一个空数组
                // 如果已经初始化了, 就直接上传即可
                // idx表示对应的 product attribute的本地数组的索引, formName是传入的上传表单的ref名称. IDX的值也通过表单提交给了服务器
                if(!this.productAttributesValues[idx]){
                    this.productAttributesValues[idx] = [];
                }
                this.$refs[formName][0].submit();
            },
            removeAttachmentExist: function(idx, theIndex){
                // 在编辑产品的时候, 这个方法用来删除原有的附件文档
                this.productAttributesValues[idx].splice(theIndex, 1);
            },
            beforeFileUploadCheck: function(file){
                // 在上传前的统一检查
                var result = true;
                if(file.size > {{ config('system.MAX_UPLOAD_FILE_SIZE') }}){
                    // 如果上传的文件大小大于2M
                    this._notify('error','Tip!','Uploading file size is too big to handle!');
                    result = false;
                }
                return result;
            }
        }
    });
</script>
