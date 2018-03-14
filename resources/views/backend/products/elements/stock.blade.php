<h5 class="desc-text">Stock Info</h5>
<el-form-item label="Manage Stock" required>
    <el-radio v-model="product.manage_stock" label="0" border>Always In Stock</el-radio>
    <el-radio v-model="product.manage_stock" label="1" border>Specify Stock Quantity</el-radio>
</el-form-item>
<el-form-item label="In Stock" prop="stock" required>
    <el-input placeholder="必填: 产品当前库存, 填写0表示有无限库存" v-model="product.stock"></el-input>
</el-form-item>
<el-form-item label="Min Quantity" prop="min_quantity" required>
    <el-input placeholder="必填: 每次购买的最少数量" v-model="product.min_quantity"></el-input>
</el-form-item>