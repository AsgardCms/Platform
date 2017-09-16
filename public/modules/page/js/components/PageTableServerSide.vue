<template>
    <div class="div">
        <div class="content-header">
            <h1>
                {{ translate('page', 'pages') }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.page.page.index'}">{{ translate('page', 'pages') }}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="sc-table">
                            <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                <div class="actions el-col el-col-5">
                                    <router-link :to="{name: 'admin.page.page.create'}">
                                        <el-button type="primary"><i class="el-icon-edit"></i>
                                            {{ translate('page', 'create page') }}
                                        </el-button>
                                    </router-link>
                                </div>
                                <div class="search el-col el-col-5">
                                    <el-input icon="search" @change="performSearch" v-model="searchQuery">
                                    </el-input>
                                </div>
                            </div>

                            <el-table
                                    :data="data"
                                    stripe
                                    style="width: 100%"
                                    v-loading.body="tableIsLoading"
                                    @sort-change="handleSortChange">
                                <el-table-column prop="id" label="Id" width="100" sortable="custom">
                                </el-table-column>
                                <el-table-column prop="translations.title" :label="translate('page', 'title')">
                                </el-table-column>
                                <el-table-column prop="translations.slug" label="Slug">
                                </el-table-column>
                                <el-table-column prop="created_at" label="Created at" sortable="custom">
                                </el-table-column>
                                <el-table-column fixed="right" prop="actions" label="Actions">
                                    <template scope="scope">
                                        <a class="btn btn-default btn-flat" @click.prevent="goToEdit(scope)"><i
                                                class="fa fa-pencil"></i></a>

                                        <delete-button :scope="scope" :rows="data" :translations="translations">
                                        </delete-button>
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
    </div>
</template>

<script>
    import axios from 'axios'
    import Translate from '../../../../Core/Assets/js/mixins/Translate'
    import _ from "lodash";

    let data;

    export default {
        mixins: [Translate],
        data() {
            return {
                data,
                meta: {},
                order_meta: {},
                links: {},
                searchQuery: '',
                tableIsLoading: false,
            }
        },
        methods: {
            fetchData() {
                this.tableIsLoading = true;
                axios.get(route('api.page.page.indexServerSide'))
                    .then(response => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
            goToEdit(scope) {
                this.$router.push({name: 'admin.page.page.edit', params: {pageId: scope.row.id}})
            },
            handleSizeChange(event) {
                console.log('per page :' + event);
                this.tableIsLoading = true;
                axios.get(route('api.page.page.indexServerSide', {
                    per_page: event,
                    page: this.meta.current_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                }))
                    .then(response => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
            handleCurrentChange(event) {
                console.log('current page :' + event);
                this.tableIsLoading = true;
                axios.get(route('api.page.page.indexServerSide', {
                    page: event,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                }))
                    .then(response => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                axios.get(route('api.page.page.indexServerSide', {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: event.prop,
                    order: event.order,
                    search: this.searchQuery,
                }))
                    .then(response => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;

                        this.order_meta.order_by = event.prop;
                        this.order_meta.order = event.order;
                    });
            },
            performSearch(query) {
                console.log('searching:' + query);
                this.tableIsLoading = true;
                axios.get(route('api.page.page.indexServerSide', {
                    search: query,
                }))
                    .then(response => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
        },
        mounted() {
            this.fetchData();
        }
    }
</script>
