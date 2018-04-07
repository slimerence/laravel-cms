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

    var SliderManagerApp = new Vue({
        el: '#sliders-manager-app',
        data: {
            slider:{
                name:'',
                wrapper_classes:'',
                attributes_text: '',
                short_code: '',
                overlay: 'is-overlay',
                interval: 5000,
                images_per_frame: 1,
                id: '',
                lib: '',
                need_thumbnail: '',
                thumbnail_position: 'none'
            },
            /**
             *  控制表单显示的标志位
             */
            showNewSliderForm: false,
            flagNewSliderImageForm: false,
            flagUploadSliderImageForm: false,
            /**
             *  控制是否按钮显示转圈的标志位
             */
            savingSlider: false,
            savingSliderImage: false,
            rules: {
                name: [
                    { required: true, message: 'Menu Name is Required', trigger: 'blur' }
                ],
                interval: [
                    { required: true, message: '动画间隔毫秒数必须填写', trigger: 'blur' }
                ],
                images_per_frame: [
                    { required: true, message: '每帧图片数必填, 最少为1', trigger: 'blur' }
                ],
                short_code:[
                    { validator: isUniqueUri, trigger: 'blur' }
                ]
            },
            // 已经存在的sliders
            sliders:[],
            // 单个slider的image对象
            sliderImage:{
                position:0,
                html_tag:'',
                classes_name:'',
                extra_html:'',
                link_to:'',
                id:'',
                media:{}
            },
            // 某个Slider的关联的图片
            sliderImages:[],
            // 最近一次上传的图片
            mediaUrlLastTime: '',
            fileList2:[]
        },
        created: function(){
            this._loadExistedSliders();
            $('#sliders-manager-app').removeClass('is-invisible');
        },
        methods: {
            /*
             *  显示新的Slider表格
             */
            createNewSliderForm: function(){
                this.showNewSliderForm = true;
                this.flagNewSliderImageForm = false;
                this._resetSliderForm();
            },
            /**
             *  清空slider表单
             */
            _resetSliderForm: function(){
                this.slider.name = '';
                this.slider.wrapper_classes = '';
                this.slider.attributes_text = '';
                this.slider.short_code = '';
                this.slider.overlay = 'is-overlay';
                this.slider.interval = 5000;
                this.slider.images_per_frame = 1;
                this.slider.id = '';
                this.slider.lib = '';
                this.slider.need_thumbnail = '';
                this.slider.thumbnail_position = 'none';
            },
            loadSliderImages: function(sliderId){
                var that = this;
                axios.get('/api/sliders/load-slider-images/' + sliderId)
                    .then(function(res){
                        if(res.data.error_no == 100){
                            console.log(res.data.data);
                        }
                    });
            },
            /**
             * 展开显示添加 Slider Images的表单
             */
            showNewSliderImageForm: function(){
                if(this.slider.id == ''){
                    this._notify('error','NOTE!','请先保存Slider再添加图片');
                }else{
                    // 重置和slider图片相关的数据
                    if(!this.flagNewSliderImageForm){
                        this.flagNewSliderImageForm = true;
                    }
                    this.mediaUrlLastTime = '';

                    // 根据已有的 Slider 图片的最大position来决定现有的position
                    if(this.sliderImages.length > 0){
                        var theLastOne = this.sliderImages[this.sliderImages.length - 1];
                        this.sliderImage.position = theLastOne.position + 10;
                    }else{
                        this.sliderImage.position = this.sliderImage.position+10;
                    }

                    this.sliderImage.html_tag = '';
                    this.sliderImage.classes_name = '';
                    this.sliderImage.extra_html = '';
                    this.sliderImage.link_to = '';
                    this.sliderImage.id = '';
                    this.sliderId = '';
                    this.fileList2 = [];
                }
            },
            /**
             * 保存当前的 Slider Image表单
             */
            saveCurrentSliderImage: function(){
                this.savingSliderImage = true;
                var that = this;

                axios.post(
                    '/api/sliders/save-slider-image',
                    {sliderImage: this.sliderImage, sliderId: this.slider.id, mediaUri: this.mediaUrlLastTime}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        if(res.data.data){
                            that.sliderImage.id = res.data.data.newSliderImageId;
                        }
                        that._notify('success','DONE!','Slider Image Saved!');
                        that.loadSlider(that.slider.id);
                    }else{
                        // 失败
                        that._notify('error','Error','Can not save slider image, please try later!');
                    }
                    that.savingSliderImage = false;
                });
            },
            /**
             * 保存当前的Slider
             */
            saveCurrentSlider: function(formName){
                var that = this;
                if(!this.slider.id){
                    this.$refs[formName].validate(
                            function(valid){
                                if (valid) {
                                    that._saveSlider();
                                } else {
                                    return false;
                                }
                            }
                    );
                }else{
                    that._saveSlider();
                }
            },
            _saveSlider: function(){
                // 保存 category 信息到服务器的方法
                this.savingSlider = true;
                var that = this;

                axios.post(
                        '/api/sliders/save',
                        {slider: this.slider}
                ).then(function(res){
                    if(res.data.error_no == 100){
                        // 成功
                        if(res.data.data){
                            that.slider.id = res.data.data.newId;
                        }
                        that._notify('success','DONE!','Slider Saved!');
                        that._loadExistedSliders();
                    }else{
                        // 失败
                        that._notify('error','Error','Can not save slider, please try later!');
                    }
                    that.savingSlider = false;
                });
            },
            _loadExistedSliders: function(){
                /**
                 * 加载已经存在的 Sliders
                 * @type {SliderManagerApp}
                 */
                var that = this;
                axios.get('/api/sliders/loadAll')
                    .then(function(res){
                        if(res.data.error_no == 100){
                            that.sliders = res.data.data;
                        }
                    });
            },
            loadSlider: function(sliderId){
                // 加载新的Slider的时候, 需要把现有的表格清空
                this.showNewSliderForm = false;
                this.flagNewSliderImageForm = false;
                // 加载指定id的slider
                var that = this;
                axios.get('/api/sliders/load/' + sliderId)
                    .then(function(res){
                        if(res.data.error_no == 100){
                            that.slider = res.data.data.slider;
                            that.sliderImages = res.data.data.sliderImages;
                            that.showNewSliderForm = true;
                        }
                    });
            },
            /**
             * 加载已经存在的slider image
             */
            editExistSliderImage: function(obj){
                this.sliderImage = obj;
                this.flagNewSliderImageForm = true;
                this.fileList2 = [];
            },
            deleteExistSliderImage: function(obj){
                console.log(obj);
            },
            /**
             * 关闭编辑slider image表单
             */
            closeCurrentSliderImageForm: function () {
                this.flagNewSliderImageForm = false;
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
            // 完全删除一个Slider的操作
            removeSlider: function(sid){
                this.$confirm('Are you sure to delete this widget?', 'Notice', {
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                })
                .then(function(){
                    axios.post('/api/sliders/delete',{
                        slider: sid,
                        user: '{{ \Illuminate\Support\Facades\Auth::user()->uuid }}'
                    }).then(function (res) {
                        if(res.data.error_no == 100){
                            window.location.href = '{{ url('backend/widgets/slider') }}';
                        }
                    });
                })
                .catch(function(){

                });
            }
        }
    });
</script>147494730 2yzj78