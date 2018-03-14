<h5 class="desc-text">Optional: Product Options&nbsp;<el-button v-on:click="addNewProductOption">+ 添加选项</el-button></h5>
<div v-show="!hideProductOptionForm" class="border-box">
    <el-form :inline="true" :model="productOptionForm" class="demo-form-inline">
        <el-form-item label="选项名称">
            <el-input v-model="productOptionForm.name" placeholder="选项名称"></el-input>
        </el-form-item>

        <el-form-item label="选项类别">
            <el-select v-model="productOptionForm.type" placeholder="选项类别">
                @foreach(\App\Models\Utils\OptionTool::AllTypes() as $key=>$type)
                    <el-option label="{{ $type }}" value="{{ $key }}"></el-option>
                @endforeach
            </el-select>
        </el-form-item>

        <el-form-item>
            <el-button type="primary" v-on:click="addNewProductOptionItem">下一步</el-button>
        </el-form-item>
    </el-form>
    <div v-show="!hideProductOptionItemForm">
        <el-form :inline="true" :model="productOptionForm" class="demo-form-inline" style="margin-left: 1%; width: 96%;">
            <el-form-item label="选项Label">
                <el-input v-model="productOptionItemForm.label" placeholder="选项Label"></el-input>
            </el-form-item>
            <el-form-item label="价格变动">
                <el-input v-model="productOptionItemForm.extra_value" placeholder="选项价格变动">
                    <template slot="prepend">{{ config('system.CURRENCY') }} (Incl GST)</template>
                </el-input>
            </el-form-item>
            <el-form-item>
                <el-button v-on:click="saveNewProductOptionItem">+</el-button>
            </el-form-item>
        </el-form>
        <div class="productOptionItemsBox" v-show="productOptionForm.items.length>0">
            <el-form :inline="true" :model="productOptionForm.items[epIdx]" class="demo-form-inline" v-for="(existProductItem, epIdx) in productOptionForm.items" :key="epIdx">
                <el-row>
                    <el-col :span="11">
                        <el-form-item label="Label">
                            <el-input v-model="productOptionForm.items[epIdx].label" placeholder="选项Label"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="11">
                        <el-form-item label="">
                            <el-input v-model="productOptionForm.items[epIdx].extra_value" placeholder="选项价格变动">
                                <template slot="prepend">{{ config('system.CURRENCY') }} (Incl GST)</template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2">
                        <el-form-item>
                            <el-button type="danger" v-on:click="removeProductOptionItem(epIdx)" icon="el-icon-delete"></el-button>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
            <el-button type="primary" v-on:click="saveNewProductOptionItemComplex" icon="el-icon-check">完成</el-button>
        </div>
    </div>
</div>
<div class="d-flex flex-wrap">
    <el-card class="box-card" v-for="(pOption,pOptionIdx) in productOptions" :key="pOptionIdx">
        <div slot="header" class="clearfix">
            <span>@{{ pOption.name }}</span>
            <el-button style="float: right; padding: 3px 0"
                       v-on:click="loadExistProductOption(pOptionIdx)"
                       type="text">修改</el-button>
            <el-button style="float: right; padding: 3px 0;color: red;margin-right: 20px;"
                       v-on:click="prepareDeleteExistProductOption(pOptionIdx)"
                       type="text">删除</el-button>
        </div>
        <div v-for="(oItem, oItemIdx) in pOption.items" :key="oItemIdx" class="text item">
            @{{ oItem.label }}: {{ config('system.CURRENCY') }} @{{ oItem.extra_value }}
        </div>
    </el-card>
</div>