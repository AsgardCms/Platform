<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <data-tables :data="data" :actions-def="actionsDef">
                        <el-table-column prop="id" label="Id" width="100"></el-table-column>
                        <el-table-column prop="title" :label="trans('pages.title')"></el-table-column>
                        <el-table-column prop="slug" label="Slug"></el-table-column>
                        <el-table-column prop="created_at" label="Created at"></el-table-column>
                        <el-table-column fixed="right" prop="actions" label="Actions">
                            <template slot-scope="scope">
                                <el-button-group>
                                    <edit-button :to="{ name: 'admin.page.page.edit', params: { pageId: scope.row.id } }"></edit-button>
                                    <delete-button :scope="scope" :rows="data" :translations="translations"></delete-button>
                                </el-button-group>
                            </template>
                        </el-table-column>
                    </data-tables>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import DeleteButton from '../../../../Core/Assets/js/components/DeleteComponent.vue';
    import EditButton from '../../../../Core/Assets/js/components/EditButtonComponent.vue';

    let data;

    export default {
        components: { DeleteButton, EditButton },
        data() {
            return {
                data,
                meta: {},
                order_meta: {},
                links: {},
                actionsDef: {
                    def: [{
                        name: this.trans('pages.create-page'),
                        icon: 'edit',
                        handler: () => {
                            this.$router.push({ name: 'admin.page.page.create' });
                        },
                    }],
                },
            };
        },
        mounted() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                axios.get(route('api.page.page.index'))
                    .then((response) => {
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
            goToEdit(scope) {
                this.$router.push({ name: 'admin.page.page.edit', params: { pageId: scope.row.id } });
            },
        },
    };
</script>
