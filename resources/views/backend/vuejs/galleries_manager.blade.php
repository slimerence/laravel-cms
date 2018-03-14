<script type="application/ecmascript">
    function isUniqueUri(rule, value, callback){
        if (!value) {
            return callback(new Error('Widget short_code is required'));
        } else {
            // 验证其唯一性
            axios.post('/api/widgets/is-uri-unique',{uri:value})
                .then(function(res){
                    if(res.data.error_no == 100 && res.data.msg == 'OK'){
                        // 表示为给定URI是唯一的
                        callback();
                    }else {
                        callback(new Error('Widget short_code must be unique, "'+value+'" has been used!'));
                    }
                });
        }
    }

    var GalleryManagerApp = new Vue({
        el: '#galleries-manager-app',
        data: {
            gallery:{
                name:'',
                wrapper_classes:'',
                attributes_text: '',
                short_code: '',
                images_per_row: 4,
                id: '',
                lib: ''
            },
            /**
             *  控制表单显示的标志位
             */
            showNewGalleryForm: false,
            flagNewGalleryImageForm: false,
            flagUploadGalleryImageForm: false,
            /**
             *  控制是否按钮显示转圈的标志位
             */
            savingGallery: false,
            savingGalleryImage: false,
            rules: {
                name: [
                    { required: true, message: 'Gallery Name is Required', trigger: 'blur' }
                ],
                images_per_row: [
                    { required: true, message: '每行图片数必填, 最少为1', trigger: 'blur' }
                ],
                short_code:[
                    { validator: isUniqueUri, trigger: 'blur' }
                ]
            },
            // 已经存在的Gallerys
            galleries:[],
            // 单个Gallery的image对象
            galleryImage:{
                position:0,
                wrapper_classes:'',
                extra_html:'',
                type:'',
                caption:'',
                id:'',
                media:{}
            },
            // 某个Gallery的关联的图片
            galleryImages:[],
            // 最近一次上传的图片
            mediaUrlLastTime: '',
            fileList2:[]
        },
        created: function(){
            this._loadExistedGalleries();
            $('#galleries-manager-app').removeClass('is-invisible');
        },
        methods: {
            /*
             *  显示新的Gallery表格
             */
            createNewGalleryForm: function(){
                this.showNewGalleryForm = true;
                this.flagNewGalleryImageForm = false;
                this._resetGalleryForm();
            },
            /**
             *  清空Gallery表单
             */
            _resetGalleryForm: function(){
                this.gallery.name = '';
                this.gallery.wrapper_classes = '';
                this.gallery.attributes_text = '';
                this.gallery.short_code = '';
                this.gallery.images_per_row = 4;
                this.gallery.id = '';
                this.gallery.lib = '';
            },
            loadGalleryImages: function(GalleryId){
                var that = this;
                axios.get('/api/galleries/load-gallery-images/' + GalleryId)
                    .then(function(res){
                        if(res.data.error_no == 100){
                            console.log(res.data.data);
                        }
                    });
            },
            /**
             * 展开显示添加 Gallery Images的表单
             */
            showNewGalleryImageForm: function(){
                if(this.gallery.id == ''){
                    this._notify('error','NOTE!','请先保存Gallery再添加图片');
                }else{
                    // 重置和Gallery图片相关的数据
                    if(!this.flagNewGalleryImageForm){
                        this.flagNewGalleryImageForm = true;
                    }
                    this.mediaUrlLastTime = '';

                    // 根据已有的Gallery图片的最大position来决定现有的position
                    if(this.galleryImages.length > 0){
                        var theLastOne = this.galleryImages[this.galleryImages.length - 1];
                        this.galleryImage.position = theLastOne.position + 10;
                    }else{
                        this.galleryImage.position = this.galleryImage.position + 10;
                    }

                    this.galleryImage.wrapper_classes = '';
                    this.galleryImage.extra_html = '';
                    this.galleryImage.caption = '';
                    this.galleryImage.type = 'img';
                    this.galleryImage.id = '';
                    this.galleryId = '';
                    this.fileList2 = [];
                }
            },
            /**
             * 保存当前的 Gallery Image表单
             */
            saveCurrentGalleryImage: function(){
                this.savingGalleryImage = true;
                var that = this;

                axios.post(
                    '/api/galleries/save-gallery-image',
                    {galleryImage: this.galleryImage, galleryId: this.gallery.id, mediaUri: this.mediaUrlLastTime}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        if(res.data.data){
                            that.galleryImage.id = res.data.data.newGalleryImageId;
                        }
                        that._notify('success','DONE!','Gallery Image Saved!');
                        that.loadGallery(that.gallery.id);
                    }else{
                        // 失败
                        that._notify('error','Error','Can not save Gallery image, please try later!');
                    }
                    that.savingGalleryImage = false;
                });
            },
            /**
             * 保存当前的Gallery
             */
            saveCurrentGallery: function(formName){
                var that = this;
                if(!this.gallery.id){
                    this.$refs[formName].validate(
                            function(valid){
                                if (valid) {
                                    that._saveGallery();
                                } else {
                                    return false;
                                }
                            }
                    );
                }else{
                    that._saveGallery();
                }
            },
            _saveGallery: function(){
                // 保存 category 信息到服务器的方法
                this.savingGallery = true;
                var that = this;

                axios.post(
                        '/api/galleries/save',
                        {gallery: this.gallery}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        if(res.data.data){
                            that.gallery.id = res.data.data.newId;
                        }
                        that._notify('success','DONE!','Gallery Saved!');
                        that._loadExistedGalleries();
                    }else{
                        // 失败
                        that._notify('error','Error','Can not save Gallery, please try later!');
                    }
                    that.savingGallery = false;
                });
            },
            _loadExistedGalleries: function(){
                /**
                 * 加载已经存在的 Galleries
                 * @type {GalleryManagerApp}
                 */
                var that = this;
                axios.get('/api/galleries/loadAll')
                    .then(function(res){
                        if(res.data.error_no == 100){
                            that.galleries = res.data.data;
                        }
                    });
            },
            loadGallery: function(GalleryId){
                // 加载新的Gallery的时候, 需要把现有的表格清空
                this.showNewGalleryForm = false;
                this.flagNewGalleryImageForm = false;
                // 加载指定id的Gallery
                var that = this;
                axios.get('/api/galleries/load/' + GalleryId)
                    .then(function(res){
                        if(res.data.error_no == 100){
                            that.gallery = res.data.data.gallery;
                            that.galleryImages = res.data.data.galleryImages;
                            that.showNewGalleryForm = true;
                        }
                    });
            },
            /**
             * 加载已经存在的Gallery image
             */
            editExistGalleryImage: function(obj){
                this.galleryImage = obj;
                this.flagNewGalleryImageForm = true;
                this.fileList2 = [];
            },
            deleteExistGalleryImage: function(obj, index){
                var that = this;
                axios.get('/api/galleries/delete_gallery_item/'+obj.id)
                    .then(function(res){
                        if(res.data.error_no == 100){
                            that.galleryImages.splice(index,1);
                        }
                    });
            },
            /**
             * 关闭编辑Gallery image表单
             */
            closeCurrentGalleryImageForm: function () {
                this.flagNewGalleryImageForm = false;
            },
            _notify: function(type, title, msg){
                // 显示弹出消息的方法
                this.$notify({title:title,message:msg,type:type});
            },
            // 图片上传相关
            handlePreview: function(file){
                console.log(file);
            },
            handleRemove: function(file, fileList){
                // 处理传出的图片, 只有一张图片,所以可以这样处理
                this.mediaUrlLastTime = '';
                this.fileList2 = [];
            },
            // 在上传图片成功之后调用
            putImagesUrlIntoList: function(response, file, fileList){
                this.mediaUrlLastTime = response.url;
            },
            // 完全删除一个Gallery的操作
            removeGallery: function(sid){
                this.$confirm('Are you sure to delete this widget?', 'Notice', {
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                })
                .then(function(){
                    axios.post('/api/galleries/delete',{
                        gallery: sid,
                        user: '{{ \Illuminate\Support\Facades\Auth::user()->uuid }}'
                    }).then(function (res) {
                        if(res.data.error_no == 100){
                            window.location.href = '{{ url('backend/widgets/gallery') }}';
                        }
                    });
                })
                .catch(function(){

                });
            }
        }
    });
</script>