<script type="application/ecmascript">
    function isUniqueUri(rule, value, callback){
        if (!value) {
            return callback(new Error('Page URI is required'));
        } else {
            // 验证其唯一性
            axios.post('/api/page/is-uri-unique',{uri:value,action:'{{ isset($actionName) ? $actionName : 'new' }}', id:'{{ $page->id }}'})
                .then(function(res){
                    if(res.data.error_no == 100 && res.data.msg == 'OK'){
                        // 表示为给定URI是唯一的
                        callback();
                    }else {
                        callback(new Error('Page URI must be unique, "' + value + '" has been used!'));
                    }
                });
        }
    }

    var PagesManager = new Vue({
        el:"#pages-manager-app",
        data: {
            savingPage: false,
            currentPage: {
                id: '{{ $page->id }}',
                title:'{{ $page->title }}',
                type:'{{ $page->type }}',   // 类型: Page, Blog or News
                layout:'{{ $page->layout }}',
                title_cn:'{{ $page->title_cn }}',
                uri:'{{ $page->uri }}',
                seo_keyword:'{{ $page->seo_keyword }}',
                seo_description:'{{ $page->seo_description }}',
                feature_image:'{{ $page->feature_image }}',
                content: '{!! $page->content !!}',
                teasing: '{!! $page->teasing !!}'
            },
            rules: {
                title: [
                    { required: true, message: 'Title is Required', trigger: 'blur' }
                ],
                teasing: [
                    { required: true, message: 'Page summary is Required', trigger: 'blur' }
                ],
                uri: [
                    { validator: isUniqueUri, trigger: 'blur' }
                ]
            }
        },
        created: function(){
            $('#pages-manager-app').removeClass('invisible');
        },
        methods: {
            savePage: function(formName){
                // 验证并保存当前正在编辑的目录信息
                var that = this;
                this.$refs[formName].validate(
                    function(valid){
                        if (valid) {
                            that._savePage();
                        } else {
                            that._notify('error','Error','表单验证失败');
                            return false;
                        }
                    }
                );
            },
            _savePage: function(){
                // 保存 category 信息到服务器的方法
                this.savingPage = true;
                var that = this;
                // 由于使用了 vuejs-editor, 需要单独通过下面的方式获取
                this.currentPage.content = this.$refs.pageContentEditor.getContent();

                axios.post(
                        '/api/page/save',
                        {page: this.currentPage}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        that._notify('success','DONE!','Page Saved!');
                        that.currentPage.id = res.data.data.msg;
                    }else{
                        // 失败
                        that._notify('error','Error','Can not save page, please try later!');
                    }
                    that.savingPage = false;
                });
            },
            _notify: function(type, title, msg){
                // 显示弹出消息的方法
                this.$notify({title:title,message:msg,type:type});
            },
            handleFeatureImageSuccess: function(res, file){
                this.currentPage.feature_image = res.url;
            },
            beforeImageUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG) {
                    this.$message.error('上传头像图片只能是 JPG 或 PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            }
        }
    });
</script>