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
                <el-autocomplete
                        v-model="groupProductSearchKeyword"
                        :fetch-suggestions="fetchRemoteProducts"
                        placeholder="请输入产品名称(至少三个字母)"
                        @select="handleGroupProductSelected"
                        clearable
                ></el-autocomplete>
            </div>
            <div class="column">
                <!-- 表单输入项 -->
                <h4>已被选择的产品</h4>
            </div>
        </div>
    </div>
</div>
