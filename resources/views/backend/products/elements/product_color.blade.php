<h5 class="desc-text">Optional: Product Colour &nbsp;<el-button v-on:click="addNewProductColour">+ Add New Color</el-button></h5>
<div class="border-box" v-show="showProductColourForm">
    <el-form :inline="true" :model="productColourForm" class="product-color-form">
        <el-form-item label="颜色名称">
            <el-input v-model="productColourForm.name" placeholder="颜色名称"></el-input>
        </el-form-item>

        <el-form-item label="颜色类别">
            <el-select v-model="productColourForm.type" placeholder="类型">
                @foreach(\App\Models\Utils\ColourTool::AllTypes() as $key=>$type)
                    <el-option label="{{ $type }}" value="{{ $key }}"></el-option>
                @endforeach
            </el-select>
        </el-form-item>

        <el-form-item label="价格变动">
            <el-input v-model="productColourForm.extra_money" placeholder="价格变动">
                <template slot="prepend">{{ config('system.CURRENCY') }}</template>
                <template slot="append">(Incl. GST)</template>
            </el-input>
        </el-form-item>
        <br>
        <el-form-item v-if="productColourForm.type=={{ \App\Models\Utils\ColourTool::$TYPE_HEX_CODE }}" label="颜色数值" class="p-color-picker-wrap">
            <el-input v-model="productColourForm.value" placeholder="选填: 颜色数值">
                <template slot="append">
                    <el-color-picker class="float-left" size="medium" v-model="productColourForm.value"></el-color-picker>
                </template>
            </el-input>
        </el-form-item>
        <br>
        <!-- 如果颜色的类型是 Image -->
        <el-form-item v-if="productColourForm.type=={{ \App\Models\Utils\ColourTool::$TYPE_IMAGE }}" label="选择图片">
            <el-upload
                    class="avatar-uploader"
                    action="{{ url('api/images/upload') }}"
                    :show-file-list="false"
                    :on-success="handleColourPictureSuccess"
                    :before-upload="beforeColourPictureUpload">
                <img v-if="productColourForm.imageUrl" :src="'/storage/'+productColourForm.imageUrl" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
        </el-form-item>
        <hr>
        <el-form-item>
            <el-button type="primary" v-on:click="saveProductColour">确认</el-button>
        </el-form-item>
    </el-form>
</div>
<div class="d-flex flex-wrap">
    <el-card class="box-card" v-for="(pColour,pColourIdx) in productColours" :key="pColourIdx">
        <div slot="header" class="clearfix">
            <span>@{{ pColour.name }}</span>
            <el-button style="float: right; padding: 3px 0"
                       v-on:click="loadExistProductColour(pColourIdx)"
                       type="text">修改</el-button>
            <el-button style="float: right; padding: 3px 0;color: red;margin-right: 20px;"
                       v-on:click="deleteExistProductColour(pColourIdx)"
                       type="text">删除</el-button>
        </div>
        <div class="text item">
            @{{ pColour.name }}: + {{ config('system.CURRENCY') }} @{{ pColour.extra_money }}
        </div>
            <div class="text item" style="min-height: 50px;" v-if="pColour.type=={{ \App\Models\Utils\ColourTool::$TYPE_HEX_CODE }}">
                <div style="min-height: 50px;width: 50px;" class="color-box" :style="{background:pColour.value}"></div>
            </div>
            <div class="text item" style="min-height: 50px;" v-if="pColour.type=={{ \App\Models\Utils\ColourTool::$TYPE_IMAGE }}">
                <img :src="'/storage/'+pColour.imageUrl" class="img-thumbnail" style="width: 50px;height: 50px;">
            </div>
    </el-card>
</div>