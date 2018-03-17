@extends('layouts.backend')

@section('content')
    <div>
        <br>
        <div class="container">
            <div class="column" id="related-products-manager">
                <div class="columns">
                    <div class="column">
                        <h2 class="is-size-4">
                            {{ trans('admin.menu.products') }} {{ trans('admin.mgr') }}
                        </h2>
                    </div>
                    <div class="column">
                        <el-button class="pull-right" :loading="saving" @click="save()" type="primary">Save All<i class="el-icon-upload el-icon--right"></i></el-button>
                    </div>
                </div>

                <el-table
                    ref="multipleTable"
                    :data="tableData"
                    tooltip-effect="dark"
                    class="table full-width is-hoverable"
                    @selection-change="handleSelectionChange"
                >
                    <el-table-column
                            type="selection"
                            width="55">
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="Product Name">
                    </el-table-column>
                </el-table>

                <div style="margin-top: 20px">
                    <el-button type="danger" icon="el-icon-circle-close" @click="toggleSelection()">Cancel All</el-button>
                </div>
            </div>
        </div>
    </div>
@endsection
