@extends('layouts.backend')

@section('content')
    <div>
        <br>
        <div class="columns" id="products-manager-app">
            <div class="column is-one-quarter">
                <div class="product-manager-title">
                    <h3>Product Manager</h3>
                </div>
                <div class="list-group product-fields-switch-btn">
                    <a href="#" class="box" v-on:click="changeTab('basic')" v-bind:class="{ 'active': currentTab=='basic' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fa fa-database" aria-hidden="true"></i>&nbsp;基本信息 <small>(必填)</small></h5>
                        </div>
                        <small>产品的基本信息</small>
                    </a>
                    <a href="#" class="box" v-on:click="changeTab('category')" v-bind:class="{ 'active': currentTab=='category' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fa fa-tags" aria-hidden="true"></i>&nbsp;产品分类与属性 <small>(必填)</small></h5>
                        </div>
                        <small>产品的分类和特殊属性信息.</small>
                    </a>
                    <a href="#" class="box" v-on:click="changeTab('price')" v-bind:class="{ 'active': currentTab=='price' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fab fa-monero" aria-hidden="true"></i>&nbsp;价格与库存 <small>(必填)</small></h5>
                        </div>
                        <small>产品的价格信息.</small>
                    </a>
                    <a href="#" class="box" v-on:click="changeTab('images')" v-bind:class="{ 'active': currentTab=='images' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fas fa-images" aria-hidden="true"></i>&nbsp;多媒体内容 <small>(必填)</small></h5>
                        </div>
                        <small>产品关联的图片或视频</small>
                    </a>
                    <a href="#" class="box" v-on:click="changeTab('productOptions')" v-bind:class="{ 'active': currentTab=='productOptions' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fa fa-puzzle-piece" aria-hidden="true"></i>&nbsp;产品选项 <small>(可选)</small></h5>
                        </div>
                        <small>供用户购买前选择的可选项目.</small>
                    </a>
                    <a href="#" class="box" v-on:click="changeTab('productColors')" v-bind:class="{ 'active': currentTab=='productColors' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fa fa-cubes" aria-hidden="true"></i>&nbsp;产品颜色 <small>(可选)</small></h5>
                        </div>
                        <small>供用户选择的颜色</small>
                    </a>
                    <a href="#" class="box" v-on:click="changeTab('seo')" v-bind:class="{ 'active': currentTab=='seo' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;搜索引擎优化 <small>(可选)</small></h5>
                        </div>
                        <small>供Google阅读的内容</small>
                    </a>
                    <a href="#" class="box" v-on:click="changeTab('group_product')" v-bind:class="{ 'active': currentTab=='group_product' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><i class="far fa-object-group"></i></i>&nbsp;创建组合产品 <small>(可选)</small></h5>
                        </div>
                        <small>组合产品选项</small>
                    </a>
                </div>
            </div>
            <div class="column is-three-quarter">
                <div class="d-flex justify-content-end">
                    <el-button-group>
                        <el-button type="primary" v-on:click="backToProducts">
                            <i class="el-icon-arrow-left"></i>&nbsp; Back
                        </el-button>


                        <el-button type="success" :loading="cloningProduct" v-on:click="cloneProduct('currentProductForm')" v-if="product.id">
                            <i class="el-icon-share"></i>&nbsp; Clone
                        </el-button>

                        <el-button type="default" v-on:click="createNewProduct">
                            <i class="el-icon-plus"></i>&nbsp; Create Another New Product
                        </el-button>

                        <el-button type="primary" :loading="savingProduct" v-on:click="saveProduct('currentProductForm')">
                            <i class="el-icon-upload2"></i>&nbsp; Save
                        </el-button>
                        <el-button type="danger" v-on:click="dialogVisible = true" v-show="product.id">
                            <i class="el-icon-delete"></i>&nbsp; Delete
                        </el-button>
                    </el-button-group>
                </div>
                <hr>
                <el-form ref="currentProductForm" :rules="rules" :model="product" label-width="160px" style="margin-left: 1%; width: 96%;">
                    <div v-show="currentTab=='basic'">
                        @include('backend.products.elements.basic')
                    </div>

                    <div v-show="currentTab=='category'">
                        @include('backend.products.elements.categories')
                    </div>

                    <div v-show="currentTab=='price'">
                        @include('backend.products.elements.stock')
                        @include('backend.products.elements.price')
                    </div>

                    <div v-show="currentTab=='images'">
                        @include('backend.products.elements.image')
                    </div>

                    <div v-show="currentTab=='productOptions'">
                        @include('backend.products.elements.product_option')
                    </div>

                    <div v-show="currentTab=='productColors'">
                        @include('backend.products.elements.product_color')
                    </div>

                    <div v-show="currentTab=='seo'">
                        @include('backend.products.elements.seo')
                    </div>
                    <div v-show="currentTab=='group_product'">
                        @include('backend.products.elements.group_product')
                    </div>
                </el-form>
            </div>
<!-- 删除product的确认框 -->
            <el-dialog
                    title="Important"
                    :visible.sync="dialogVisible"
                    width="30%">
                <span>Are you sure to delete this product?</span>
                      <span slot="footer" class="dialog-footer">
                          <el-button v-on:click="dialogVisible = false">Cancel</el-button>
                          <el-button type="danger" v-on:click="deleteSelectedProduct">Yes</el-button>
                      </span>
            </el-dialog>

<!-- 删除product option的确认框 -->
            <el-dialog
                    title="Important"
                    :visible.sync="dialogDeleteProductOptionVisible"
                    width="30%">
                <span>Are you sure to delete this product option? (It's Undoable!)</span>
                      <span slot="footer" class="dialog-footer">
                          <el-button v-on:click="cancelDeleteExistProductOption">Cancel</el-button>
                          <el-button type="danger" v-on:click="deleteExistProductOption">Yes</el-button>
                      </span>
            </el-dialog>
        </div>
    </div>
@endsection
