@extends('layouts.backend')

@section('content')
    <div class="content">
        <br>
        <div class="columns is-invisible" id="galleries-manager-app">
            <div class="column is-one-fifth">
                <h2 class="is-size-4">Widgets - Galleries</h2>
                <hr>
                <div class="is-10 is-offset-1">
                    <div class="box" v-for="(theGallery, idx) in galleries" :key="idx">
                        <article class="tile is-child notification">
                            <p class="is-size-5"><a href="#" @click.prevent="loadGallery(theGallery.id)">@{{ theGallery.name }}</a></p>
                            <br>
                            <p class="subtitle has-text-right">
                                <a href="#" class="button is-danger" @click.prevent="removeGallery(theGallery.id)">
                                    Delete
                                </a>
                            </p>
                        </article>
                    </div>
                </div>
            </div>
            <div class="column is-four-fifth">
                <p class="has-text-right">
                    <button class="button is-primary" v-on:click="createNewGalleryForm">
                        <i class="el-icon-plus"></i>&nbsp; New Gallery
                    </button>
                </p>
                <hr>
                <div class="row" v-show="showNewGalleryForm">
                    <el-form ref="currentGallery" status-icon :rules="rules" :model="gallery" label-width="160px">
                        <el-form-item label="Gallery Name" prop="name" required>
                            <el-input placeholder="名称: 必填" v-model="gallery.name"></el-input>
                        </el-form-item>

                        <el-form-item label="Type" prop="lib" required>
                            <el-select v-model="gallery.lib" placeholder="请选择">
                                <el-option label="Fancybox" value="Fancybox"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="Short Code" prop="short_code" required>
                            <el-input placeholder="短码, 用于插入到页面中时使用: 必填" v-model="gallery.short_code"></el-input>
                        </el-form-item>

                        <el-form-item label="Images/Row" prop="images_per_row" required>
                            <el-input placeholder="每行显示几张图片: 必填" v-model="gallery.images_per_row"></el-input>
                        </el-form-item>

                        <el-form-item label="Wrapper CSS Classes" prop="wrapper_classes">
                            <el-input placeholder="Wrapper 需要附加的CSS类名, 使用空格做分隔符: 选填" type="textarea" v-model="gallery.wrapper_classes"></el-input>
                        </el-form-item>
                        <el-form-item label="Attributes" prop="attributes_text">
                            <el-input placeholder="需要附加的属性名/值对: 选填" type="textarea" v-model="gallery.attributes_text"></el-input>
                        </el-form-item>
                        <hr>
                        <el-form-item>
                            <el-button :loading="savingGallery" type="primary" v-on:click="saveCurrentGallery('currentGallery')">
                                Save<i class="el-icon-upload el-icon--right"></i>
                            </el-button>
                            <el-button>Reset</el-button>
                            <el-button v-on:click="showNewGalleryImageForm">
                                Add Gallery Item<i class="el-icon-plus el-icon--right"></i>
                            </el-button>
                        </el-form-item>
                    </el-form>
                </div>

                <div class="row" v-show="flagNewGalleryImageForm">
                    <el-form ref="currentGalleryImage" :model="galleryImage" label-width="160px">
                        <el-form-item label="Order" prop="position" required>
                            <el-input placeholder="排序: 必填" v-model="galleryImage.position"></el-input>
                        </el-form-item>

                        <el-form-item label="Caption">
                            <el-input placeholder="标题: 选填" v-model="galleryImage.caption"></el-input>
                        </el-form-item>

                        <el-form-item label="Type" required>
                            <el-select v-model="galleryImage.type" placeholder="请选择">
                                <el-option label="Image" value="img"></el-option>
                                <el-option label="Video" value="video"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="Wrapper CSS类名" prop="wrapper_classes">
                            <el-input placeholder="Wrapper对应的CSS类名: 选填" type="textarea" v-model="galleryImage.wrapper_classes"></el-input>
                        </el-form-item>
                        <el-form-item label="内嵌的HTML" prop="extra_html">
                            <el-input placeholder="内嵌的HTML: 选填" type="textarea" v-model="galleryImage.extra_html"></el-input>
                        </el-form-item>
                        <hr>
                        <el-form-item label="图片上传" prop="link_to">
                            <el-upload
                                    class="upload-demo"
                                    ref="galleryImageUploader"
                                    action="{{ url('api/images/upload') }}"
                                    :on-success="putImagesUrlIntoList"
                                    :on-preview="handlePreview"
                                    :on-remove="handleRemove"
                                    :file-list="fileList2"
                                    :multiple="false"
                                    list-type="picture">
                                <el-button size="small" type="primary">点击添加/更新图片</el-button>
                                <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过1MB</div>
                            </el-upload>
                        </el-form-item>

                        <hr>
                        <el-form-item>
                            <el-button :loading="savingGalleryImage" type="primary" v-on:click="saveCurrentGalleryImage">
                                Save<i class="el-icon-upload el-icon--right"></i>
                            </el-button>
                            <el-button v-on:click="closeCurrentGalleryImageForm">Close</el-button>
                        </el-form-item>
                    </el-form>
                </div>

                <div class="row" v-show="showNewGalleryForm">
                    <el-row style="width: 90%;margin-left: 5%;">
                        <el-col :span="5" v-for="(o, index) in galleryImages" :key="index" :offset="1">
                            <el-card :body-style="{ padding: '0px' }">
                                <div style="padding: 0px;height: 300px;overflow: hidden">
                                    <img :src="o.media.url" class="image" style="width: 100%;" @click.prevent="editExistGalleryImage(o)">
                                </div>
                                <div style="padding: 14px;">
                                    <p @click.prevent="editExistGalleryImage(o)" class="has-text-centered mb-10">Caption: @{{ o.caption }}</p>
                                    <div class="bottom clearfix">
                                        <a @click.prevent="editExistGalleryImage(o)" type="text" class="button">编辑</a>
                                        <a @click.prevent="deleteExistGalleryImage(o,index)" type="text" class="button is-danger">Delete</a>
                                    </div>
                                </div>
                            </el-card>
                        </el-col>
                    </el-row>
                </div>
            </div>
        </div>
    </div>
@endsection
