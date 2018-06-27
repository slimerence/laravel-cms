<?php
if($product_option){
$uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
?>
<div class="product-option-input-wrap">
    <div class="form-group row">
        <input type="hidden" id="product_option_{{ $uuid }}" name="product_option_{{ $product_option->id }}"
               data-type="attachment" value="{{ $product_option->id }}"
        >
        <div class="col-sm-12">
            <el-upload
                    class="avatar-uploader"
                    action="{{ url('/api/files/upload') }}"
                    :data="{directReturn:<?php echo $key; ?>}"
                    :show-file-list="false"
                    :on-success="handleAttachmentSuccess"
                    :before-upload="beforeAttachmentUpload">
                <img v-if="somethingAttached" :src="_getAttachmentUrl({{$key}})" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                <div class="el-upload__text">Upload {{ $product_option->name }}</div>
            </el-upload>
        </div>
    </div>
</div>
<?php
}
?>