<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="sc-table">
                <div class="tool-bar el-row" style="padding-bottom: 20px;">
                    <div class="actions el-col el-col-8">
                        <h4>{{ trans('media.choose file') }}</h4>
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
                        ref="mediaTable"
                        v-loading.body="tableIsLoading"
                        @sort-change="handleSortChange">
                    <el-table-column label="" width="150">
                        <template scope="scope">
                            <img :src="scope.row.small_thumb" alt="" v-if="scope.row.is_image"/>
                            <i :class="`fa ${scope.row.fa_icon}`" style="font-size: 20px;" v-if="! scope.row.is_image"></i>
                        </template>
                    </el-table-column>
                    <el-table-column prop="filename" :label="trans('media.table.filename')" sortable="custom">
                    </el-table-column>
                    <el-table-column prop="created_at" :label="trans('core.table.created at')" sortable="custom">
                    </el-table-column>
                    <el-table-column prop="actions" label="" width="150">
                        <template scope="scope">
                            <a class="btn btn-primary btn-flat" @click.prevent="insertMedia(scope)" v-if="singleModal">
                                {{ trans('media.insert') }}
                            </a>
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
</template>

<script>
    export default {
        props: {
            singleModal: {type: Boolean},
        },
        data() {
            return {
                data: [],
                tableIsLoading: false,
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
            }
        },
        methods: {
            queryServer(customProperties) {
                let properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                };

                axios.get(route('api.media.all-vue', _.merge(properties, customProperties)))
                    .then(response => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;

                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            fetchMediaData() {
                this.tableIsLoading = true;
                this.queryServer();
            },
            handleSizeChange(event) {
                console.log('per page :' + event);
                this.tableIsLoading = true;
                this.queryServer({per_page: event});
            },
            handleCurrentChange(event) {
                console.log('current page :' + event);
                this.tableIsLoading = true;
                this.queryServer({page: event});
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                this.queryServer({order_by: event.prop, order: event.order,});
            },
            performSearch(query) {
                console.log('searching:' + query);
                this.tableIsLoading = true;
                this.queryServer({search: query});
            },
            insertMedia(scope) {
                this.$events.emit('fileWasSelected', scope.row);
            },
        },
        mounted() {
            this.fetchMediaData();
            this.$events.listen('fileWasUploaded', eventData => {
                this.fetchMediaData();
            });
        }
    }
</script>
