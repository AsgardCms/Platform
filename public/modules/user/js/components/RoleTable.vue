<template>
    <div>
        <div class="content-header">
            <h1>
                {{ trans('roles.title.roles') }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">{{ trans('core.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.roles.index'}">{{ trans('roles.title.roles') }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="sc-table">
                            <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                <div class="actions el-col el-col-8">
                                    <router-link :to="{name: 'admin.user.roles.create'}">
                                        <el-button type="primary"><i class="el-icon-edit"></i>
                                            {{ trans('roles.new-role') }}
                                        </el-button>
                                    </router-link>
                                </div>
                                <div class="search el-col el-col-5">
                                    <el-input prefix-icon="el-icon-search" @keyup.native="performSearch" v-model="searchQuery">
                                    </el-input>
                                </div>
                            </div>

                            <el-table
                                    :data="data"
                                    stripe
                                    style="width: 100%"
                                    ref="pageTable"
                                    v-loading.body="tableIsLoading"
                                    @sort-change="handleSortChange">
                                <el-table-column prop="id" label="Id" width="75" sortable="custom">
                                </el-table-column>
                                <el-table-column prop="name" :label="trans('roles.table.name')" sortable="custom">
                                    <template slot-scope="scope">
                                        <a @click.prevent="goToEdit(scope)" href="#">
                                            {{ scope.row.name }}
                                        </a>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="slug" :label="trans('roles.table.slug')" sortable="custom">
                                    <template slot-scope="scope">
                                        <a @click.prevent="goToEdit(scope)" href="#">
                                            {{ scope.row.slug }}
                                        </a>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="created_at" :label="trans('core.table.created at')"
                                                 sortable="custom">
                                </el-table-column>
                                <el-table-column prop="actions" :label="trans('core.table.actions')">
                                    <template slot-scope="scope">
                                        <el-button-group>
                                            <edit-button
                                                    :to="{name: 'admin.user.roles.edit', params: {roleId: scope.row.id}}"></edit-button>
                                            <delete-button :scope="scope" :rows="data"></delete-button>
                                        </el-button-group>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
                                <el-pagination
                                        @size-change="handleSizeChange"
                                        @current-change="handleCurrentChange"
                                        :current-page.sync="meta.current_page"
                                        :page-sizes="[10, 20, 50, 100]"
                                        :page-size="parseInt(meta.per_page)"
                                        layout="total, sizes, prev, pager, next, jumper"
                                        :total="meta.total">
                                </el-pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button v-shortkey="['c']" @shortkey="pushRoute({name: 'admin.user.roles.create'})" v-show="false"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';

    export default {
        mixins: [ShortcutHelper],
        data() {
            return {
                data: [],
                meta: {
                    current_page: 1,
                    per_page: 10,
                },
                order_meta: {
                    order_by: '',
                    order: '',
                },
                links: {},
                searchQuery: '',
                tableIsLoading: false,
            };
        },
        methods: {
            queryServer(customProperties) {
                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                };

                axios.get(route('api.user.role.index', _.merge(properties, customProperties)))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;

                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            fetchData() {
                this.tableIsLoading = true;
                this.queryServer();
            },
            handleSizeChange(event) {
                console.log(`per page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ per_page: event });
            },
            handleCurrentChange(event) {
                console.log(`current page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ page: event });
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                this.queryServer({ order_by: event.prop, order: event.order });
            },
            performSearch: _.debounce(function (query) {
                console.log(`searching:${query.target.value}`);
                this.tableIsLoading = true;
                this.queryServer({ search: query.target.value });
            }, 300),
            goToEdit(scope) {
                this.$router.push({ name: 'admin.user.roles.edit', params: { roleId: scope.row.id } });
            },
        },
        mounted() {
            this.fetchData();
        },
    };
</script>
