<h5 class="desc-text">Basic Price Info</h5>
<el-form-item label="Price" prop="default_price" required>
    <el-input placeholder="必填: 产品基本价格" v-model="product.default_price">
        <template slot="prepend">{{ config('system.CURRENCY') }} (Excl. GST)</template>
        <el-button slot="append" icon="el-icon-sold-out" @click="removeGstDefault"></el-button>
    </el-input>
</el-form-item>
<el-form-item label="Special Price">
    <el-input placeholder="选填: 产品优惠价格" v-model="product.special_price">
        <template slot="prepend">{{ config('system.CURRENCY') }} (Excl. GST)</template>
        <el-button slot="append" icon="el-icon-sold-out" @click="removeGstSpecial"></el-button>
    </el-input>
</el-form-item>
<el-form-item label="GST">
    <el-input placeholder="必填: GST" v-model="product.tax">
        <template slot="append">%</template>
    </el-input>
</el-form-item>

<el-form-item label="Product Unit">
    <el-input placeholder="选填: 产品的单位, 例如 Carton" v-model="product.unit_text">
    </el-input>
</el-form-item>