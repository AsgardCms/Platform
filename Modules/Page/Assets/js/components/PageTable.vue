<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <el-table
                            :data="data"
                            stripe
                            style="width: 100%"
                            @sort-change="handleSortChange">
                        <el-table-column prop="id" label="Id" width="100" sortable>
                        </el-table-column>
                        <el-table-column prop="translations.title" :label="translate('page', 'title')" sortable>
                        </el-table-column>
                        <el-table-column prop="translations.slug" label="Slug" sortable>
                        </el-table-column>
                        <el-table-column prop="created_at" label="Created at" sortable>
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
                    <div class="sc-table">
                        <div class="pagination-wrap">
                            <el-pagination
                                    @size-change="handleSizeChange"
                                    @current-change="handleCurrentChange"
                                    :current-page.sync="meta.current_age"
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
</template>

<script>
    import axios from 'axios'
    import Translate from '../../../../Core/Assets/js/mixins/Translate'

    let data;

    export default {
        mixins: [Translate],
        data() {
            return {
                data,
                meta: {},
                links: {},
            }
        },
        methods: {
            fetchData() {
                axios.get(route('api.page.page.index'))
                    .then(response => {
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
                axios.get(route('api.page.page.index', {per_page: event, page: this.meta.current_page}))
                    .then(response => {
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
            handleCurrentChange(event) {
                console.log('current page :' + event);
                axios.get(route('api.page.page.index', {page: event, per_page: this.meta.per_page}))
                    .then(response => {
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
            handleSortChange(event) {
                console.log('sorting', event);

                axios.get(route('api.page.page.index', {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: event.prop,
                    order: event.order,
                }))
                    .then(response => {
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
