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
    export default {
        name:'VuejsEditor',
        props:{
            textAreaId: String,
            originalContent: String,
            placeholder: String,
            imageUploadUrl: String, // 保存图片的url
            existedImages: String,  // 加载已经存在的图片的资源url
            shortCodesLoadUrl: String, // 加载Variables的url
            options: Array
        },
        data: function(){
            return {
                content: '',
                editor:null,
                buttons:[
                  'table','alignment','fontcolor','source','imagemanager','video','filemanager','variable','counter','definedlinks','fontfamily','fontsize','properties','widget','imagecontrol'
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
            axios.get(this.shortCodesLoadUrl)
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
                        plugins: this.buttons,
                        imageUpload: this.imageUploadUrl,
                        fileUpload: this.imageUploadUrl,
                        variables:this.shortCodes,
                        definedlinks:this.definedLinks,
                        imageResizable: true,
                        imagePosition: true,
                        imageFloatMargin: '20px'
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