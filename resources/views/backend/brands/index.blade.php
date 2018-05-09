@extends('layouts.backend')
@section('content')
    <div id="brands-manager-app">
        <br>
        <div class="columns">
            <div class="column">
                <h2 class="is-size-4">
                    {{ trans('admin.menu.brands') }} {{ trans('admin.mgr') }} ({{ $brands->total() }})
                </h2>
            </div>
            <div class="column">
                <a class="button is-primary pull-right" href="{{ url('/backend/brands/add') }}"><i class="fa fa-plus"></i>&nbsp;{{ trans('admin.new.brands') }}</a>
            </div>
        </div>

        <div class="container">
            <table class="table full-width is-hoverable">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>状态</th>
                    <th>Logo图片</th>
                    <th>品牌子序列</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($brands as $key=>$value)
                    <tr>
                        <td>
                            {!! $value->name !!}
                        </td>
                        <td>
                            {!! $value->status ? '<span class="tag is-success">上线</span>' : '<span class="tag is-danger">下线</span>' !!}
                            &nbsp;
                            {!! $value->status ? '<span class="tag is-success">推广中</span>' : '<span class="tag is-light">未推广</span>' !!}
                        </td>
                        <td>
                            <figure class="image" style="width: 100px;">
                                <img src="{{ $value->getImageUrl() }}">
                            </figure>
                        </td>
                        <td>
                            <div id="brand-serials-{{ $value->id }}" class="tags">
                                @foreach($value->serials as $key=>$brandSerial)
                                <span class="tag" v-on:click="editSerialForm({{ $brandSerial->id }})">{{ $brandSerial->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <a class="button is-small" v-on:click="showSerialForm({{ $value->id }},'{{ $value->name }}',$event)" href="#">
                                <i class="fa fa-plus"></i>&nbsp;Serial
                            </a>
                            <a class="button is-small" href="{{ url('backend/brands/edit/'.$value->id) }}">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                            <a class="button is-danger is-small btn-delete" href="{{ url('backend/brands/delete/'.$value->id) }}">
                                <i class="fa fa-trash"></i>&nbsp;Del
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $brands->links() }}
        </div>

        <el-dialog :title="serialForm.selectedBrandName" :visible.sync="serialFormVisible">
            <el-form :model="serialForm">
                <el-form-item label="Serial 名称" :label-width="formLabelWidth">
                    <el-input v-model="serialForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="Serial的Keywords" :label-width="formLabelWidth">
                    <el-input
                            type="textarea"
                            autosize
                            placeholder="选填: 请输入keywords, 用空格做间隔"
                            v-model="serialForm.keyword">
                    </el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button class="is-pulled-left" type="danger" @click="deleteSerial" :loading="isDeleting">删 除</el-button>
                <el-button type="primary" @click="saveSerial" :loading="isSaving">确 定</el-button>
            </div>
        </el-dialog>
    </div>
@endsection