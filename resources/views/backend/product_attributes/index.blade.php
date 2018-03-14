@extends('layouts.backend')

@section('content')
    <div>
        <br>
        <div class="columns" id="attributes-manager-app">
            <div class="column is-one-quarter">
                <h2 class="is-size-4">Set name: <span class="text-primary">{{ $attributeSet->name }}</span></h2>
                <br>
                <div class="list-group">
                    @foreach($parentAttributes as $key=>$parentAttribute)
                    <a href="#" class="box mb-10"
                       v-on:click="changeTab('{{ $parentAttribute->id }}')"
                       v-bind:class="{ 'active': currentTab=='{{ $parentAttribute->id }}' }">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $parentAttribute->name }} (<small>{{ $parentAttribute->attributeSet->name }}</small>)</h5>

                        </div>
                        <small>{{ \App\Models\Utils\OptionTool::TypeName($parentAttribute->type) }}: {{ $parentAttribute->default_value }}</small>
                    </a>
                    @endforeach

                    @foreach($attributes as $key=>$attribute)
                        <a href="#" class="box mb-10"
                           v-on:click="changeTab('{{ $attribute->id }}')"
                           v-bind:class="{ 'active': currentTab=='{{ $attribute->id }}' }">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $attribute->name }} (<small>{{ $attribute->attributeSet->name }}</small>)</h5>
                            </div>
                            <small>类型: {{ \App\Models\Utils\OptionTool::TypeName($attribute->type) }}</small>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="column is-three-quarter">
                <div class="has-text-right">
                    <el-button-group>
                        <el-button type="default" v-on:click="goBack">
                            <i class="el-icon-arrow-left"></i>&nbsp; Go Back
                        </el-button>
                        <el-button type="primary" v-on:click="createNewAttribute">
                            <i class="el-icon-plus"></i>&nbsp; Create New Attribute
                        </el-button>
                        <el-button type="primary" :loading="savingAttribute" v-on:click="saveAttribute('productAttributeForm')">
                            <i class="el-icon-upload2"></i>&nbsp; Save
                        </el-button>
                        <el-button type="danger" v-on:click="dialogVisible = true">
                            <i class="el-icon-delete"></i>&nbsp; Delete
                        </el-button>
                    </el-button-group>
                </div>
                <hr>

                <div>
                    <el-form ref="productAttributeForm" :rules="rules" :model="productAttribute" label-width="120px">
                        <el-form-item label="属性名称" prop="name" required>
                            <el-input placeholder="必填: 属性名称" v-model="productAttribute.name"></el-input>
                        </el-form-item>
                        <el-form-item label="类型">
                            <el-select v-model="productAttribute.type" placeholder="必填: 属性类型">
                                @foreach(\App\Models\Utils\OptionTool::ProductAttributeTypes() as $key=>$typeName)
                                    <el-option label="{{ $typeName }}" value="{{ $key }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>

                        <el-form-item label="显示位置">
                            <el-select v-model="productAttribute.location" placeholder="Type">
                                @foreach(\App\Models\Utils\OptionTool::AttributeLocations() as $key=>$typeName)
                                    <el-option label="{{ $typeName }}" value="{{ $key }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>

                        <el-form-item label="顺序编号">
                            <el-input v-model="productAttribute.position"></el-input>
                        </el-form-item>

                        <el-form-item label="默认值">
                            <el-input type="textarea" v-model="productAttribute.default_value"></el-input>
                        </el-form-item>

                    </el-form>
                </div>
            </div>
            <el-dialog
                    title="Important"
                    :visible.sync="dialogVisible"
                    width="30%">
                <span>Are you sure to delete this attribute?</span>
                      <span slot="footer" class="dialog-footer">
                          <el-button v-on:click="dialogVisible = false">Cancel</el-button>
                          <el-button type="danger" v-on:click="deleteCurrentProductAttribute">Yes</el-button>
                      </span>
            </el-dialog>
        </div>
    </div>
@endsection
