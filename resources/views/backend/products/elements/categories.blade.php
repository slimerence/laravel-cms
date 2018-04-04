<h5 class="desc-text">Categories</h5>
<div class="columns">
@foreach($categories as $key=>$category)
    <div class="column is-2 form-check form-check-inline">
        <label class="checkbox form-check-label">
            <input v-model="categories" class="checkbox form-check-input"
                   type="checkbox" value="{{ $category->id }}"> {{ $category->name }}
        </label>
    </div>
@endforeach
</div>
<hr>
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
                    :text-area-id="'product-attribute-vue-js-editor'+idx"
                    :original-content="productAttributesValues[idx]"
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
                    <a v-bind:href="'/storage/'+item.url" target="_blank">@{{ item.name }}</a>
                    <el-button v-on:click="removeAttachmentExist(item.idx, theIndex)" type="danger" size="mini" icon="el-icon-delete"></el-button>
                </div>
            </div>
        </el-form-item>
    </div>
</div>