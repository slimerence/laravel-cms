@extends('layouts.backend')

@section('content')
    <div class="content">
        <br>
        <div class="columns is-invisible" id="sliders-manager-app">
            <div class="column is-one-fifth">
                <h2 class="is-size-4">Widgets - Sliders</h2>
                <hr>
                <div class="is-10 is-offset-1">
                    <div class="box" v-for="(theSlider, idx) in sliders" :key="idx">
                        <article class="tile is-child notification">
                            <p class="is-size-5"><a href="#" @click.prevent="loadSlider(theSlider.id)">@{{ theSlider.name }}</a></p>
                            <br>
                            <p class="subtitle has-text-right"><a href="#" class="button is-danger" @click.prevent="removeSlider(theSlider.id)">Delete</a></p>
                        </article>
                    </div>
                </div>
            </div>
            <div class="column is-four-fifth">
                <p class="has-text-right">
                    <button class="button is-primary" v-on:click="createNewSliderForm">
                        <i class="el-icon-plus"></i>&nbsp; New Slider
                    </button>
                </p>
                <hr style="margin-top: -1px;">
                <div class="row" v-show="showNewSliderForm">
                    <el-form ref="currentSlider" status-icon :rules="rules" :model="slider" label-width="160px">
                        <el-form-item label="Slider Name" prop="name" required>
                            <el-input placeholder="名称: 必填" v-model="slider.name"></el-input>
                        </el-form-item>

                        <div class="columns">
                            <div class="column">
                                <el-form-item  label="Type">
                                    <el-select v-model="slider.lib" placeholder="请选择">
                                        <el-option label="Slick" value="slick"></el-option>
                                        <el-option label="Carousel" value="carousel"></el-option>
                                    </el-select>
                                </el-form-item>
                            </div>
                            <div class="column">
                                <el-form-item  label="Need Thumbnail">
                                    <el-select v-model="slider.need_thumbnail" placeholder="请选择">
                                        <el-option label="Yes" value="1"></el-option>
                                        <el-option label="No" value="0"></el-option>
                                    </el-select>
                                </el-form-item>
                            </div>
                            <div class="column">
                                <el-form-item  label="Thumbnail Position">
                                    <el-select v-model="slider.thumbnail_position" placeholder="请选择">
                                        <el-option label="None" value="none"></el-option>
                                        <el-option label="Bottom" value="bottom"></el-option>
                                        <el-option label="Right" value="right"></el-option>
                                        <el-option label="Top" value="top"></el-option>
                                        <el-option label="Left" value="left"></el-option>
                                    </el-select>
                                </el-form-item>
                            </div>
                        </div>

                        <el-form-item label="Short Code" prop="short_code" required>
                            <el-input placeholder="短码, 用于插入到页面中时使用: 必填" v-model="slider.short_code"></el-input>
                            <span class="has-text-grey-light">专用于首页的Slider的短代码: {{ \App\Models\Widget\Slider::HOME_SLIDER_KEY }}</span>
                        </el-form-item>

                        <el-form-item label="Interval" prop="interval" required>
                            <el-input placeholder="动画间隔毫秒数: 必填" v-model="slider.interval"></el-input>
                        </el-form-item>

                        <el-form-item label="Images/Frame" prop="images_per_frame" required>
                            <el-input placeholder="每次显示几张图片: 必填" v-model="slider.images_per_frame"></el-input>
                        </el-form-item>

                        <el-form-item  label="Arrows Position">
                            <el-select v-model="slider.overlay" placeholder="请选择">
                                <el-option label="Overlay" value="is-overlay"></el-option>
                                <el-option label="Underneath" value=""></el-option>
                                <el-option label="Centered" value="is-centered"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="CSS Classes" prop="wrapper_classes">
                            <el-input placeholder="需要附加的CSS类名, 使用空格做分隔符: 选填" type="textarea" v-model="slider.wrapper_classes"></el-input>
                        </el-form-item>
                        <el-form-item label="Attributes" prop="attributes_text">
                            <el-input placeholder="需要附加的属性名/值对: 选填" type="textarea" v-model="slider.attributes_text"></el-input>
                        </el-form-item>
                        <hr>
                        <el-form-item>
                            <el-button :loading="savingSlider" type="primary" v-on:click="saveCurrentSlider('currentSlider')">
                                Save<i class="el-icon-upload el-icon--right"></i>
                            </el-button>
                            <el-button>Reset</el-button>
                            <el-button v-on:click="showNewSliderImageForm">
                                Add Image<i class="el-icon-plus el-icon--right"></i>
                            </el-button>
                        </el-form-item>
                    </el-form>
                </div>

                <div class="row" v-show="flagNewSliderImageForm">
                    <el-form ref="currentSliderImage" :model="sliderImage" label-width="160px">
                        <el-form-item label="Order" prop="position" required>
                            <el-input placeholder="排序: 必填" v-model="sliderImage.position"></el-input>
                        </el-form-item>

                        <el-form-item label="HTML标签" prop="html_tag">
                            <el-input placeholder="HTML标签, 留空则使用DIV: 选填" type="textarea" v-model="sliderImage.html_tag"></el-input>
                        </el-form-item>

                        <el-form-item label="CSS类名" prop="classes_name">
                            <el-input placeholder="对应的CSS类名: 选填" type="textarea" v-model="sliderImage.classes_name"></el-input>
                        </el-form-item>
                        <el-form-item label="内嵌的HTML" prop="extra_html">
                            <el-input placeholder="内嵌的HTML: 选填" type="textarea" v-model="sliderImage.extra_html"></el-input>
                        </el-form-item>
                        <el-form-item label="链接URL" prop="link_to">
                            <el-input placeholder="链接URL: 选填" type="textarea" v-model="sliderImage.link_to"></el-input>
                        </el-form-item>
                        <hr>
                        <el-form-item label="图片上传" prop="link_to">
                            <el-upload
                                    class="upload-demo"
                                    ref="slideImageUploader"
                                    action="{{ url('api/images/upload') }}"
                                    :on-success="putImagesUrlIntoList"
                                    :on-preview="handlePreview"
                                    :on-remove="handleRemove"
                                    :file-list="fileList2"
                                    :multiple="false"
                                    list-type="picture">
                                <el-button size="small" type="primary">点击添加/更新图片</el-button>
                                <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                            </el-upload>
                        </el-form-item>

                        <hr>
                        <el-form-item>
                            <el-button :loading="savingSliderImage" type="primary" v-on:click="saveCurrentSliderImage">
                                Save<i class="el-icon-upload el-icon--right"></i>
                            </el-button>
                            <el-button v-on:click="closeCurrentSliderImageForm">Close</el-button>
                        </el-form-item>
                    </el-form>
                </div>

                <div class="row" v-show="showNewSliderForm">
                    <el-row style="width: 90%;margin-left: 5%;">
                        <el-col :span="5" v-for="(o, index) in sliderImages" :key="index" :offset="1">
                            <el-card :body-style="{ padding: '0px' }">
                                <div style="padding: 0px;height: 300px;overflow: hidden">
                                    <img :src="o.media.url" class="image" style="width: 100%;">
                                </div>
                                <div style="padding: 14px;">
                                    <span>@{{ o.name }}</span>
                                    <div class="bottom clearfix">
                                        <a @click.prevent="editExistSliderImage(o)" type="text" class="button">编辑</a>
                                        <a @click.prevent="deleteExistSliderImage(o)" type="text" class="button is-danger">Delete</a>
                                    </div>
                                </div>
                            </el-card>
                        </el-col>
                    </el-row>
                </div>

                <div class="row slider-preview-wrapper" v-if="sliderImages.length > 0"  style="margin-top: 40px;margin-left: 30px;">
                    <h2>Preview</h2>
                    <el-carousel :interval="2000" arrow="always" style="width: 100%;">
                        <el-carousel-item v-for="(item,idx) in sliderImages" :key="idx">
                            <img :src="item.media.url" alt="" style="width: 100%;">
                        </el-carousel-item>
                    </el-carousel>
                </div>
            </div>
        </div>
    </div>
@endsection
