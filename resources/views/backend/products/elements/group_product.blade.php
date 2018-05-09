<h5>组合产品相关属性</h5>
<hr>
<el-switch
        v-model="product.is_group_product"
        active-text="是组合产品"
        inactive-text="不是组合产品"
        v-on:change="switchOnAddGroupProductForm"
>
</el-switch>

<div v-show="product.is_group_product && product.id">
    <hr>

    <div class="container">
        <div class="columns">
            <div class="column is-4">
                <!-- 产品选择器 -->
                <h4>请选择产品</h4>
                <br>
                <el-form ref="newGroupProductForm" label-width="80px">
                    <label for="">产品定位</label>
                    <p>
                        <el-autocomplete
                            class="full-width"
                            v-model="groupProductSearchKeyword"
                            :fetch-suggestions="fetchRemoteProducts"
                            placeholder="请输入产品名称(至少三个字母)"
                            :trigger-on-focus="false"
                            @select="handleGroupProductSelected"
                        ></el-autocomplete>
                    </p>
                    <div style="margin: 20px 0;"></div>
                    <el-form-item label="产品数量">
                        <el-input placeholder="只能输入阿拉伯数字" v-model="tempGroupProductQuantity"></el-input>
                    </el-form-item>
                    <div style="margin: 20px 0;"></div>
                    <el-input
                            type="textarea"
                            autosize
                            placeholder="选填: 说明文字"
                            v-model="tempGroupProductNotes">
                    </el-input>
                    <div style="margin: 20px 0;"></div>
                    <el-form-item>
                        <el-button type="primary" @click="confirmToAddThisGroupProduct">添加此产品</el-button>
                        <el-button>清空</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="column">
                <!-- 表单输入项 -->
                <h4>已被选择的产品</h4><br>
                <div class="existed-grouped-products-wrap" v-for="(existedProduct, idx) in existedGroupProducts">
                    <p :key="idx" class="mb-10">
                        <el-tag
                            :key="idx"
                            closable
                            :disable-transitions="false"
                            @close="removeExistGroupProduct(idx, existedProduct.id)">
                            @{{ existedProduct.name }}
                        </el-tag>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
