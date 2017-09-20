<style>
    .new-folder {
        float: left;
        margin-right: 10px;
    }
</style>
<template>
    <div>
        <el-button type="success" class="new-folder" @click="dialogFormVisible = true">
            <i class="el-icon-fa-plus"></i> New Folder
        </el-button>

        <el-dialog title="New Folder" :visible.sync="dialogFormVisible" size="tiny">
            <el-form :model="folder" v-loading.body="loading">
                <el-form-item label="Folder name" :class="{'el-form-item is-error': form.errors.has('name') }">
                    <el-input v-model="folder.name" auto-complete="off"></el-input>
                    <div class="el-form-item__error" v-if="form.errors.has('name')"
                         v-text="form.errors.first('name')"></div>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
            <el-button @click="closeDialog">Cancel</el-button>
            <el-button type="primary" @click="onSubmit()">Confirm</el-button>
          </span>
        </el-dialog>

    </div>
</template>

<script>
    import Form from 'form-backend-validation'

    export default {
        data() {
            return {
                dialogFormVisible: false,
                folder: {
                    name: '',
                },
                form: new Form(),
                loading: false,
            }
        },
        methods: {
            onSubmit() {
                this.form = new Form(this.folder);
                this.loading = true;
                this.form.post(route('api.media.folders.store'))
                    .then(response => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message
                        });
                        this.dialogFormVisible = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                        this.$notify.error({
                            title: 'Error',
                            message: 'There are some errors in the form.'
                        });
                    });
            },
            closeDialog() {
                this.form.clear();
                this.dialogFormVisible = false;
            },
        },
        mounted() {
        }
    }
</script>
