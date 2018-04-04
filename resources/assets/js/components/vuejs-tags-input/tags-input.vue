<template>
    <div id="tags-input-wrap">
        <input v-model="inputText" class="input" type="tags" :placeholder="tips" value="Tag1,Tag2,Tag3">
    </div>
</template>

<script type="text/ecmascript-6">
    const CATALOG_VIEWER_LEFT_SECTION_WIDTH = 213;
    export default {
        name:'TagsInput',
        props:{
            // 大尺寸图片
            tips:{
                type: String,
                required: true
            },
            values:{
                type: Array,
                required: true
            }
        },
        data: function(){
            return {
                inputText: ''
            }
        },
        watch: {
        },
        created() {
        },
        mounted() {
        },
        methods:{
            buildCategoryViewLink: function(id){
                return '/' + this.categoryLoadingUrl + '/' + id;
            },
            buildProductViewLink: function(id){
                return '/' + this.productLoadingUrl + '/' + id;
            },
            buildBrandViewLink: function(brandName){
                return '/' + this.brandLoadingUrl + '?brand=' + brandName;
            },
            inCategoryItemSectionHandler: function(){
                this.showCurrentCategoryDetailFlag = true;
            },
            outCategoryItemSectionHandler: function(){
                this.showCurrentCategoryDetailFlag = false;
            },
            /**
             * 根据鼠标的hover位置加载目录信息
             * @param id
             */
            loadCategoryDetail: function(id){
                let idx = _.findIndex(this.firstLevelCategories, function(cat){
                    return cat.id == id;
                });
                if(idx > -1){
                    this.currentCategory =  this.firstLevelCategories[idx];
                }
            }
        }
    }
</script>
<style scoped lang="scss" rel="stylesheet/scss">
    // css rule here
    #tags-input-wrap{
        position: relative;
        width: 100%;
        height: auto;
    }
</style>