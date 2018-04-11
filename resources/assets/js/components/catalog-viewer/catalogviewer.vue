<template>
    <div id="catalog-viewer-wrap">
        <div class="vue-js-catalog-viewer-wrap" :style="{'height': '0px','width': width+'px'}" :class="{'hidden':!isShowSubsNow}">
            <div class="vue-js-categories-list" :style="{'width': firstLevelCategoriesWrapperWidth + 'px','backgroundColor': categoriesListWrapperBackgroundColor}">
                <ul class="the-list" v-on:mouseover="inCategoryItemSectionHandler($event)" v-on:mouseout="outCategoryItemSectionHandler($event)">
                    <li v-for="(flc, idx) in firstLevelCategories" :key="idx" class="flc-item" @mouseover="loadCategoryDetail(flc.id)">
                        <a class="has-text-white" :href="buildCategoryViewLink(flc.uri)">{{ flc.name }}</a>
                    </li>
                    <li id="details-wrapper">
                        <div class="vue-js-sub-category-details-wrapper"
                             :style="{'width': currentCategoryDetailsWrapperWidth + 'px', 'left':firstLevelCategoriesWrapperWidth+'px', 'top':-firstLevelCategories.length*46+'px'}"
                             :class="{'hidden': !showCurrentCategoryDetailFlag}"
                        >
                            <div class="columns">
                                <div class="column" :class="{'is-9':currentCategory.images&&currentCategory.images.length>0}">
                                    <div class="brands-wrap" v-if="currentCategory.brands">
                                        <div class="brand" v-for="(brand, idx) in currentCategory.brands" :key="idx">
                                            <a :href="buildBrandViewLink(brand.name)">{{ brand.name }}</a>
                                        </div>
                                    </div>
                                    <ul class="sub-cats-wrap" v-if="currentCategory.subs">
                                        <li class="sub-cat" v-for="(subCat, idx) in currentCategory.subs" :key="idx">
                                            <div class="wrap">
                                                <div class="columns">
                                                    <div class="column is-3">
                                                        <div class="sub-cat-name">
                                                            <a :href="buildCategoryViewLink(subCat.uri)">{{ subCat.name }}<i class="fas fa-angle-right"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="column is-9 products">
                                                        <div class="product" v-for="(p,pidx) in subCat.products" :key="pidx">
                                                            <a :href="buildProductViewLink(p.uri)">{{ p.name }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="columns is-multiline my-products-wrap" v-if="currentCategory.products && currentCategory.products.length>0">
                                        <div class="column is-3" v-for="(myProduct, idx) in currentCategory.products" :key="idx" v-show="myProduct.image_path">
                                            <div class="box">
                                                <a :href="buildProductViewLink(myProduct.uri)">
                                                    <img class="image" :src="myProduct.image_path">
                                                    {{ myProduct.name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column" :class="{'is-3':currentCategory.images&&currentCategory.images.length>0}" v-if="currentCategory.images&&currentCategory.images.length>0">
                                    <div class="img-wrap" v-for="(image, idx) in currentCategory.images">
                                        <a href="image.link">
                                            <img :src="image.url" alt="Image">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    const CATALOG_VIEWER_LEFT_SECTION_WIDTH = 213;
    export default {
        name:'CatalogViewer',
        props:{
            // 大尺寸图片
            categoryLoadingUrl:{
                type: String,
                required: true
            },
            productLoadingUrl:{
                type: String,
                required: false
            },
            brandLoadingUrl:{
                type: String,
                required: false
            },
            firstLevelCategories:{
                type: Array,
                required: true
            },
            width:{
                type: Number,
                required: true
            },
            height:{
                type: Number,
                required: true
            },
            leftWidth:{
                type: Number,
                required: false
            },
            showNow: {
                type: Boolean,
                required: true
            },
            showBy: {
                type: String,
                required: false
            },
            triggerId: {
                type: String,
                required: false
            },
            categoriesListBgColor: {
                type: String,
                required: false
            }
        },
        data: function(){
            return {
                categoriesListWrapperBackgroundColor: 'rgba(0, 0, 0, 0.8)',
                firstLevelCategoriesWrapperWidth: null,     // 第一级目录区宽度
                currentCategoryDetailsWrapperWidth: null,   // 当前目录区宽度
                currentCategory:{},                         // 当前hover到的category
                inCategoryItemSection: false,               // 鼠标在目录项的范围内
                inCategoryDetail: false,                    // 鼠标在目录详情的范围内
                showCurrentCategoryDetailFlag: false,       // 是否显示当前hover的目录的详情
                isShowSubsNow: false                        // 是否显示子菜单
            }
        },
        watch: {
        },
        created() {
            // 下拉的子目录北京颜色
            if(this.categoriesListBgColor){
                this.categoriesListWrapperBackgroundColor = this.categoriesListBgColor;
            }
            // 左侧目录树的宽度与右侧展示区的宽度
            this.firstLevelCategoriesWrapperWidth = this.leftWidth ? this.leftWidth : CATALOG_VIEWER_LEFT_SECTION_WIDTH;
            this.currentCategoryDetailsWrapperWidth = this.width - this.firstLevelCategoriesWrapperWidth;
        },
        mounted() {
            this.isShowSubsNow = this.showNow;
            if(!this.isShowSubsNow){
                // 如果不是默认显示子菜单
                if(this.showBy === 'hover' && $(this.triggerId).length >0){
                    // 表示要通过hover的方式触发子菜单
                    $(this.triggerId).on('mouseover',e => {
                        this.isShowSubsNow = true;
                    });
                    $(this.triggerId).on('mouseout',e => {
                        this.isShowSubsNow = false;
                    });
                }
            }
        },
        methods:{
            buildCategoryViewLink: function(id){
                return '/' + this.categoryLoadingUrl + '/' + id;
            },
            buildProductViewLink: function(id){
                return '/' + this.productLoadingUrl + '/' + id;
            },
            buildBrandViewLink: function(brandName){
                return '/catalog/brand/load?name=' + brandName;
            },
            inCategoryItemSectionHandler: function(e){
                this.showCurrentCategoryDetailFlag = true;
            },
            outCategoryItemSectionHandler: function(e){
                this.showCurrentCategoryDetailFlag = false;
            },
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
    #catalog-viewer-wrap{
        position: relative;
        width: 100%;
        height: auto;
        .vue-js-catalog-viewer-wrap{
            position: absolute;
            top:0;
            left:0;
            z-index: 1000;
            background-color: transparent;
            height: auto;
            .vue-js-categories-list{
                /*background-color: rgba(0, 0, 0, 0.8);*/
                height: auto;
                float: left;
                margin:0;
                padding: 0;
                .the-list{
                    .flc-item{
                        line-height: 30px;
                        font-size: 16px;
                        color: #fff;
                        padding: 8px 0px 8px 18px;
                        font-weight: 100;
                        &:hover{
                            background-color: #999395;
                            color: #b92e2d;
                        }
                    }
                    #details-wrapper{
                        position:relative;
                        .vue-js-sub-category-details-wrapper{
                            position: absolute;
                            margin: 0;
                            padding: 14px;
                            height: 600px;
                            background-color: white;
                            border: solid 1px #ccc;
                            .brands-wrap{
                                display: flex;
                                margin-bottom: 20px;
                                .brand{
                                    background-color: #6e6568;
                                    line-height: 28px;
                                    font-size: 16px;
                                    padding-left:10px;
                                    padding-right:10px;
                                    margin-right: 10px;
                                    a{
                                        color: white;
                                    }
                                    &:hover{
                                        background-color: #999395;
                                        color: #b92e2d;
                                    }
                                }
                            }
                            .my-products-wrap{
                                .image{
                                    max-height: 200px;
                                }
                            }
                            .sub-cats-wrap{
                                display: flex;
                                flex-direction: column;
                                .sub-cat{
                                    width: 100%;
                                    margin-bottom: 16px;
                                    .wrap{
                                        .sub-cat-name{
                                            padding-right: 20px;
                                            text-align: right;
                                            line-height: 30px;
                                            font-size: 14px;
                                            font-weight: bold;
                                            border-right: solid 1px #e0e0e0;
                                            a{
                                                color:#666;
                                                i, svg{
                                                    margin-left: 14px;
                                                    font-size:20px;
                                                }
                                                &:hover{
                                                    color: #b92e2d;
                                                }
                                            }
                                        }
                                        .products{
                                            display: flex;
                                            padding-bottom: 10px;
                                            margin-bottom: 10px;
                                            border-bottom: solid 1px #e0e0e0;
                                            .product{
                                                line-height: 30px;
                                                font-size: 14px;
                                                padding-right: 16px;
                                                margin-right: 16px;
                                                border-right: solid 1px #e0e0e0;
                                                a{
                                                    color: #666;
                                                    &:hover{
                                                        color: #b92e2d;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            .img-wrap{
                                text-align: right;
                                img{
                                    width: 90%;
                                    margin-bottom: 10px;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
</style>