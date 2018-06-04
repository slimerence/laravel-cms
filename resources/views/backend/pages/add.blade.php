@extends('layouts.backend')

@section('content')
    <div id="pages-manager-app" class="invisible">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ isset($actionName) ? trans('admin.'.$actionName.'.'.$menuName) : trans('admin.new.'.$menuName) }}
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/'.$menuName.'/index') }}"><i class="fas fa-arrow-left"></i>&nbsp;Back</a>
            </div>
        </div>

        <div class="container">
            <el-form ref="currentPage" status-icon :rules="rules" :model="currentPage" label-width="160px">
                <el-form-item label="Title" prop="title" required>
                    <el-input placeholder="Required: Page title" v-model="currentPage.title"></el-input>
                </el-form-item>
                <el-form-item label="中文Title" prop="title_cn">
                    <el-input placeholder="中文名称: 必填" v-model="currentPage.title_cn"></el-input>
                </el-form-item>
                <el-form-item label="URI" prop="uri" required>
                    <el-input placeholder="必填: 网址的URI" v-model="currentPage.uri"></el-input>
                </el-form-item>
                <el-form-item label="Page Layout">
                    <el-select v-model="currentPage.layout" placeholder="请选择页面布局">
                        @foreach(\App\Models\Utils\ContentTool::GetPageLayoutTypes() as $key=>$type)
                            <el-option label="{{ $type }}" value="{{ $key }}"></el-option>
                        @endforeach
                    </el-select>
                </el-form-item>

                <el-form-item label="Feature Image">
                    <el-upload
                            class="avatar-uploader"
                            action="{{ url('/api/images/upload') }}"
                            :multiple="false"
                            :show-file-list="false"
                            :on-success="handleFeatureImageSuccess"
                            :before-upload="beforeImageUpload">
                        <img v-if="currentPage.feature_image" :src="currentPage.feature_image" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>

                <el-form-item label="SEO Keywords">
                    <el-input type="textarea" placeholder="Optional" v-model="currentPage.seo_keyword"></el-input>
                </el-form-item>

                <el-form-item label="SEO Description">
                    <el-input type="textarea" placeholder="Optional" v-model="currentPage.seo_description"></el-input>
                </el-form-item>

                <hr>
                <el-form-item label="Summary" prop="teasing" required>
                    <el-input type="textarea" placeholder="Required: Page summary 页面内容简介" v-model="currentPage.teasing"></el-input>
                </el-form-item>

                <el-form-item label="Content">
                    <vuejs-editor
                            ref="pageContentEditor"
                            class="rich-text-editor"
                            placeholder="Put content here"
                            text-area-id="page-content-editor"
                            :original-content="currentPage.content"
                    ></vuejs-editor>
                </el-form-item>
                <el-button type="primary" :loading="savingPage" v-on:click="savePage('currentPage')">
                    <i class="el-icon-upload2"></i>&nbsp; Save
                </el-button>
            </el-form>
        </div>
    </div>
@endsection
