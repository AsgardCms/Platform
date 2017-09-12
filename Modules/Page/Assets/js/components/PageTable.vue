<template>
    <data-tables :data="data" :actions-def="actionsDef">
        <el-table-column prop="id" label="Id" width="100">
        </el-table-column>
        <el-table-column prop="title" :label="translate('page', 'title')">
        </el-table-column>
        <el-table-column prop="slug" label="Slug">
        </el-table-column>
        <el-table-column prop="created_at" label="Created at">
        </el-table-column>
        <el-table-column fixed="right" prop="actions" label="Actions">
            <template scope="scope">
                <a class="btn btn-default btn-flat" @click.prevent="goToEdit(scope)"><i class="fa fa-pencil"></i></a>

                <delete-button :scope="scope" :rows="data" :translations="translations">
                </delete-button>
            </template>
        </el-table-column>
    </data-tables>
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
                actionsDef: {
                    def: [{
                        name: this.translate('page', 'create page'),
                        icon: 'edit',
                        handler: () => {
                            window.location = route('admin.page.page.create');
                        }
                    }]
                }
            }
        },
        methods: {
            fetchData() {
                let vm = this;
                axios.get(route('api.page.page.index'))
                    .then(response => {
                        vm.data = response.data.data;
                    });
            },
            goToEdit(scope) {
                window.location = scope.row.urls.edit_url;
            },
        },
        mounted() {
            this.fetchData();
        }
    }
</script>
