<h5 class="desc-text">Optional: Product Images</h5>
<el-upload
        ref="productImagesUploader"
        action="{{ url('api/images/upload') }}"
        list-type="picture-card"
        :multiple="true"
        :on-success="putImagesUrlIntoList"
        :on-preview="handlePictureCardPreview"
        :on-remove="handleProductImageRemove"
        :before-upload="beforeFileUploadCheck"
>
    <i class="el-icon-plus"></i>
    <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且文件大小不超过500kb</div>
</el-upload>
<el-dialog v-model="productDialogVisible" size="tiny">
    <img width="100%" :src="productImageUrl" alt="">
</el-dialog>
<hr>
<div class="columns is-multiline">
    <div class="column is-one-quarter" v-for="(pImage, index) in productImages" :key="index">
        <div class="card">
            <div class="card-image">
                <figure class="image is-4by3">
                    <img :src="pImage.url" :alt="pImage.alt">
                </figure>
            </div>
            <div class="card-content">
                <div class="content">
                    <p>文件大小: @{{ pImage.size }}K</p>
                    <time datetime="">宽: @{{ pImage.width }}px, 高: @{{ pImage.height }}px </time>
                    <br>
                    <p class="has-text-right">
                        <el-button v-on:click="removeExistImage(index)" size="mini" type="danger" icon="el-icon-delete" round></el-button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>