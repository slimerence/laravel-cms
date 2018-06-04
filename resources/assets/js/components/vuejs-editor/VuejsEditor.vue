<template>
    <div class="">
        <div class="vue-js-editor">
            <textarea :id="textAreaId" :placeholder="placeholder" cols="30" rows="10">{{originalContent}}</textarea>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    require('./lib3/redactor3')
    require('./lib3/plugins/variable')
    require('./lib3/plugins/fontcolor')
    require('./lib3/plugins/video')
    require('./lib3/plugins/imagemanager')
    require('./lib3/plugins/filemanager')
    require('./lib3/plugins/table')
    require('./lib3/plugins/counter')
    require('./lib3/plugins/alignment')
    require('./lib3/plugins/definedlinks')
    require('./lib3/plugins/fontfamily')
    require('./lib3/plugins/fontsize')
    require('./lib3/plugins/properties')
    require('./lib3/plugins/widget')
    require('./lib3/plugins/imagecontrol')

    const DEFAULT_IMAGE_UPLOAD_URL  = '/api/images/upload';     // 默认的上传图片保存路由
    const DEFAULT_IMAGE_MANAGER_URL = '/api/images/load-all';   // 默认的加载已存在图片路由
    const DEFAULT_FILE_UPLOAD_URL   = '/api/files/upload';      // 默认的上传文件保存路由
    const DEFAULT_FILE_MANAGER_URL  = '/api/files/load-all';    // 默认的加载已存在文件路由
    const DEFAULT_SHORT_CODES_URL   = '/api/widgets/load-short-codes';    // 默认的加载已存在短码的URL
    const DEFAULT_IMAGE_FLOAT_MARGIN  = 20;                     // 默认的加载已存在文件路由

    export default {
        name:'VuejsEditor',
        props:{
            textAreaId: {
                type: String,
                required: true
            },
            originalContent: String,
            placeholder: String,
            options: Array,
            shortCodesLoadUrl: {    // 加载Variables的url
                type: String,
                required: false
            },
            imageUploadUrl: {     // 保存图片的url
                type: String,
                required: false
            },
            existedImages: {     // 加载已经存在的图片的资源url
                type: String,
                required: false
            },
            fileUploadUrl: {     // 保存图片的url
                type: String,
                required: false
            },
            existedFiles: {
                type: String,
                required: false
            }
        },
        data: function(){
            return {
                content: '',
                editor:null,
                buttons:[
                    'table','alignment','fontcolor','source','imagemanager',
                    'video','filemanager','variable','counter',
                    'definedlinks','fontfamily','fontsize','properties',
                    'widget','imagecontrol'
                ],
                images: [],
                shortCodes:[],
                definedLinks:[
                    { "name": "Select...", "url": false },
                    { "name": "Google", "url": "http://google.com" },
                    { "name": "Home", "url": "/" },
                    { "name": "About", "url": "/about-us/" },
                    { "name": "Contact", "url": "/contact-us/" }
                ]
            }
        },
        watch: {
            'originalContent': function(newContent, oldContent){
                if(newContent != oldContent){
                    this.setContent(newContent);
                }
            }
        },
        created() {
            _.each(this.options, (option)=>{
                if(this.buttons.indexOf(option) !== false){
                    this.buttons.push(option)
                }
            });
        },
        mounted() {
            let _shortCodesLoadingUrl = this.shortCodesLoadUrl ? this.shortCodesLoadUrl : DEFAULT_SHORT_CODES_URL;
            axios.get(_shortCodesLoadingUrl)
                .then(res => {
                    if(res.data.error_no == 100){
                        this.shortCodes = res.data.data;
                        this._editorInit();
                    }
                });
        },
        methods:{
            getContent: function(){
                return $R('#'+this.textAreaId, 'source.getCode');
            },
            setContent: function(html){
                $('#'+this.textAreaId).redactor('source.setCode', html);
            },
            isMe: function(code){
                // 用来甄别是否和传入的code匹配, 从而起到甄别是否在操作当前组件的作用
                return code == this.uniqueCode;
            },
            _editorInit: function(){
                $R('#' + this.textAreaId,
                    {
                        plugins:            this.buttons,
                        imageUpload:        this.imageUploadUrl ? this.imageUploadUrl : DEFAULT_IMAGE_UPLOAD_URL,
                        imageManagerJson:   this.existedImages ? this.existedImages : DEFAULT_IMAGE_MANAGER_URL,   // 加载已经被添加的图片, 并进行选择
                        fileUpload:         this.fileUploadUrl ? this.fileUploadUrl : DEFAULT_FILE_UPLOAD_URL,
                        fileManagerJson:    this.existedFiles ? this.existedFiles : DEFAULT_FILE_MANAGER_URL,     // 加载已经被添加的附件文件， 并进行选择
                        variables:          this.shortCodes,
                        definedLinks:       this.definedLinks,
                        imageResizable:     true,
                        imagePosition:      true,
                        imageFloatMargin:   DEFAULT_IMAGE_FLOAT_MARGIN + 'px'
                    }
                );
            }
        }
    }
</script>
<style scoped lang="scss" rel="stylesheet/scss">
    // css rule here
    .vueJsEditorWrap{
        .vue-js-editor{

        }
    }
</style>