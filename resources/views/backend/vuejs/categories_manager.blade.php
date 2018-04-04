<script type="application/ecmascript">
    var CategoriesManager = new Vue({
        el:"#categories-manager-app",
        data: {
            savingCategory: false,
            currentParentCategoryId: 1,
            currentSelectedCategoryName: 'Root',
            highlightSelected: true,
            dialogVisible: false,
            categories: [],
            // 控制目录显示的对象
            defaultProps: {
                children: 'children',
                label: 'name'
            },
            currentCategory: {
                id: null,
                name:'',
                position:0,
                parent_id: 1,
                short_description: '',
                keywords:'',
                seo_description: '',
                include_in_menu: false,
                as_link: false,
                brands:[]
            },
            rules: {
                name: [
                    { required: true, message: 'Category Name Required', trigger: 'blur' }
                ],
                short_description: [
                    { required: true, message: 'Short Description Required', trigger: 'blur' }
                ]
            },
            brandsData: []
        },
        created: function(){
            this.loadCategoriesTree();
            $('#categories-manager-app').removeClass('invisible');
            // 加载所有的Brands
            this._loadAllBrands(this.currentCategory.id);
        },
        methods: {
            // 和Brands相关
            _loadAllBrands: function(id){
                var that = this;
                axios.get(
                    '/api/category/load-brands/' + id
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that.brandsData = res.data.data.brands;
                        that.currentCategory.brands = res.data.data.categoryBrands;
                    }else{
                        // 失败
                        that._notify('error','Error','Something wrong, please refresh the page!');
                    }
                });
            },
            // 和品牌Brands相关结束
            loadCategoriesTree: function(){
                // 加载目录树的操作
                var that = this;
                axios.get(
                        '/api/category/tree'
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that.categories = res.data.data.children;
                    }else{
                        // 失败
                        that._notify('error','Error','Something wrong, please refresh the page!');
                    }
                });
            },
            renderCategoryLabel: function(h, { node, data, store }){
                return window.categoryNoteRender(h, { node, data, store });
            },
            handleEdit: function(cate, node, tree){
                console.log(cate);
                // 加载选定的目录树种的目录,放到待编辑表单中
                this.currentParentCategoryId = cate.id;
                this.currentSelectedCategoryName = cate.name;
                this.currentCategory.id = cate.id;
                this.currentCategory.name = cate.name;
                this.currentCategory.position = cate.position;
                this.currentCategory.parent_id = cate.parent_id;
                this.currentCategory.short_description = cate.short_description;
                this.currentCategory.keywords = cate.keywords;
                this.currentCategory.seo_description = cate.seo_description;
                this.currentCategory.include_in_menu = cate.include_in_menu;
                this.currentCategory.as_link = cate.as_link;
                this.currentCategory.brands = cate.brands;
            },
            createNewCategoryForm: function(){
                // 点击 New Category之后, 将表格设置为新添状态. 以当前选定的目录作为父目录
                this.currentCategory.id = null;
                this.currentCategory.name = '';
                this.currentCategory.position = 0;
                this.currentCategory.parent_id = this.currentParentCategoryId;
                this.currentCategory.short_description = '';
                this.currentCategory.keywords = '';
                this.currentCategory.seo_description = '';
                this.currentCategory.include_in_menu = false;
                this.currentCategory.as_link = false;
                this.currentCategory.brands = [];
            },
            createNewRootCategoryForm: function(){
                // 创建一个新的顶级目录
                this.currentParentCategoryId = 1;
                this.currentSelectedCategoryName = 'Root';
                this.createNewCategoryForm();
            },
            saveCurrentCategory: function(formName){
                // 验证并保存当前正在编辑的目录信息
                var that = this;
                this.$refs[formName].validate(
                    function(valid){
                        if (valid) {
                            that._saveCategory();
                        } else {
                            console.log('error submit!!');
                            return false;
                        }
                    }
                );
            },
            deleteSelectedCategory: function(){
                // 删除所选定的目录的操作
                if(this.currentCategory.id){
                    var that = this;
                    axios.post(
                            '/api/category/delete',
                            {category: this.currentCategory.id}
                    ).then(function(res){
                        if(res.data.error_no == 100){
                            // 成功
                            that._notify('success','DONE!','Category Deleted!');
                            that.loadCategoriesTree();
                            that.createNewRootCategoryForm();
                        }else{
                            // 失败
                            that._notify('error','Error','Can not delete category, please try later!');
                        }
                    });
                }
                this.dialogVisible = false;
            },
            _saveCategory: function(){
                // 保存 category 信息到服务器的方法
                this.savingCategory = true;
                var that = this;
                // 由于使用了 vuejs-editor, 需要单独通过下面的方式获取产品的description最新值
                this.currentCategory.short_description = this.$refs.categoryShortDescriptionEditor.getContent();
                axios.post(
                    '/api/category/save',
                    {category: this.currentCategory}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that._notify('success','DONE!','Category Saved!');
                        that.loadCategoriesTree();
                    }else{
                        // 失败
                        that._notify('error','Error','Can not save category, please try later!');
                    }
                    that.savingCategory = false;
                });
            },
            _notify: function(type, title, msg){
                // 显示弹出消息的方法
                this.$notify({title:title,message:msg,type:type});
            }
        }
    });
</script>