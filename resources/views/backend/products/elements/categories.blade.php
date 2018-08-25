<h5 class="desc-text">属性集</h5>
<div>
    <el-form-item label="Attribute Set" class="select">
        <select class="form-control" v-model="currentAttributeSetId" placeholder="请选择">
            @foreach($attributesSet as $set)
                <option value="{{ $set->id }}" {{ $product->attribute_set_id == $set->id ? 'selected' : null }}>{{ $set->name }}</option>
            @endforeach
        </select>
    </el-form-item>

    <div v-for="(pAttribute, idx) in productAttributes" :key="idx">
        <el-form-item :label="pAttribute.name" v-if="pAttribute.type=={{ \App\Models\Utils\OptionTool::$TYPE_TEXT }}">
            <el-input :placeholder="pAttribute.default_value" v-model="productAttributesValues[idx]"></el-input>
        </el-form-item>

        <el-form-item :label="pAttribute.name" v-if="pAttribute.type=={{ \App\Models\Utils\OptionTool::$TYPE_RICH_TEXT }}">
            <vuejs-editor
                    :ref="'productAttribute'+idx"
                    class="rich-text-editor"
                    :text-area-id="'product-attribute-'+idx"
                    :original-content="productAttributesValues[idx]"
                    image-upload-url="/api/images/upload"
                    existed-images="/api/images/load-all"
                    short-codes-load-url="/api/widgets/load-short-codes"
                    placeholder="(必填) Product Attribute Content"
            ></vuejs-editor>
        </el-form-item>

        <el-form-item :label="pAttribute.name" v-if="pAttribute.type=={{ \App\Models\Utils\OptionTool::$TYPE_VIDEO }}">
            <el-input placeholder="Embed Codes from Youtube" type="textarea" v-model="productAttributesValues[idx]"></el-input>
        </el-form-item>

        <el-form-item :label="pAttribute.name" v-if="pAttribute.type=={{ \App\Models\Utils\OptionTool::$TYPE_ATTACHMENT }}">
            <el-upload
                class="upload-demo"
                :data="{index:idx}"
                :ref="'productAttachmentsUploader'+idx"
                action="{{ url('api/attachments/upload') }}"
                :on-preview="handleAttachmentPreview"
                :on-remove="handleAttachmentRemove"
                :on-success="putAttachmentUrlIntoList"
                :before-upload="beforeFileUploadCheck"
                :auto-upload="false"
            >
                <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
                <el-button style="margin-left: 10px;" size="small" type="success" v-on:click="submitAttachment(idx,'productAttachmentsUploader'+idx)">上传到服务器</el-button>
                <div slot="tip" class="el-upload__tip">只能上传PDF文件，且不超过2M</div>
            </el-upload>
            <div class="exist-attachment-wrap">
                <div class="exist-attachment-item" v-for="(item, theIndex) in productAttributesValues[idx]" v-if="item.idx">
                    <a v-bind:href="item.url" target="_blank">@{{ item.name }}</a>
                    <el-button v-on:click="removeAttachmentExist(item.idx, theIndex)" type="danger" size="mini" icon="el-icon-delete"></el-button>
                </div>
            </div>
        </el-form-item>
    </div>
</div>
<hr>
<h5 class="desc-text">Categories</h5>
<div class="content">
    @foreach($categoriesTree['children'] as $key=>$levelOne)
        <article class="message is-info">
            <div class="message-header">
                <label class="checkbox form-check-label">
                    <input v-model="categories" class="checkbox form-check-input"
                       type="checkbox" value="{{ $levelOne['id'] }}"> {{ $levelOne['name'] }}
                </label>
            </div>
            @if(count($levelOne['children'])>0)
                <div class="message-body">
                    @foreach($levelOne['children'] as $key=>$levelTwo)
                        <p>
                            <label class="checkbox form-check-label">
                                <input v-model="categories" class="checkbox form-check-input"
                                   type="checkbox" value="{{ $levelTwo['id'] }}"> - {{ $levelTwo['name'] }}
                            </label>
                        </p>
                        @if(isset($levelTwo['children']) && count($levelTwo['children']))
                            <div class="panel" style="padding-left: 40px;">
                                @foreach($levelTwo['children'] as $levelThree)
                                    <p>
                                        <label class="form-check-label" style="margin-right: 20px;">
                                            <input v-model="categories" class="checkbox form-check-input"
                                                   type="checkbox" value="{{ $levelThree['id'] }}"> -- {{ $levelThree['name'] }}
                                        </label>
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </article>
    @endforeach
</div>
<h5 class="desc-text">Tags</h5>

<div class="content">
    <el-select
            v-model="tags"
            multiple
            filterable
            allow-create
            default-first-option
            value-key="id"
            placeholder="请选择产品标签">
        <el-option
                v-for="(item, idx) in tagsNamelist"
                :key="idx"
                :label="item.name"
                :value="item">
        </el-option>
    </el-select>
</div>
