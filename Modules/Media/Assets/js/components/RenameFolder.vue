<template>
    <div>
        <el-dialog title="Rename Folder" :visible.sync="dialogFormVisible" width="30%">
            <el-form :model="folder" v-loading.body="loading" @submit.native.prevent="onSubmit()">
                <el-form-item label="Folder name" :class="{'el-form-item is-error': form.errors.has('name') }">
                    <el-input v-model="folder.name" auto-complete="off" autofocus></el-input>
                    <div class="el-form-item__error" v-if="form.errors.has('name')"
                         v-text="form.errors.first('name')"></div>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
            <el-button @click="closeDialog">{{ trans('core.button.cancel') }}</el-button>
            <el-button type="primary" @click="onSubmit()">{{ trans('core.confirm') }}</el-button>
          </span>
        </el-dialog>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';

    export default {
//        props: {
//            currentFolder: {type: Object}
//        },
        data() {
            return {
                dialogFormVisible: false,
                folder: {
                    name: '',
                    id: '',
                    parent_id: '',
                },
                form: new Form(),
                loading: false,
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(this.folder);
                this.loading = true;
                this.form.post(route('api.media.folders.update', { folder: this.folder.id }))
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.dialogFormVisible = false;
                        this.$events.emit('folderWasUpdated', response);
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                        this.$notify.error({
                            title: 'Error',
                            message: 'There are some errors in the form.',
                        });
                    });
            },
            closeDialog() {
                this.form.clear();
                this.dialogFormVisible = false;
            },
        },
        mounted() {
            this.$events.listen('editFolderWasClicked', (eventData) => {
                this.folder.name = eventData.filename;
                this.folder.id = eventData.id;
                this.folder.parent_id = eventData.folder_id;
                this.dialogFormVisible = true;
            });
        },
    };
</script>
