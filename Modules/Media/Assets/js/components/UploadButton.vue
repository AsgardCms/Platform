<template>
    <el-upload
            class="upload-demo"
            :action="uploadUrl"
            :on-remove="handleRemove"
            :on-success="handleSuccess"
            :show-file-list="false"
            :http-request="uploadFile"
            :multiple="true"
            style="display: inline-block; margin-right: 10px;">
        <el-button size="small" type="primary" style="padding: 13px 9px;" :loading="fileIsUploading">{{ trans('media.upload file') }}</el-button>
    </el-upload>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            parentId: { type: Number },
        },
        data() {
            return {
                fileIsUploading: false,
            };
        },
        computed: {
            uploadUrl() {
                return route('api.media.store').domain + route('api.media.store').url;
            },
            requestHeaders() {
                const userApiToken = document.head.querySelector('meta[name="user-api-token"]');
                return {
                    Authorization: `Bearer ${userApiToken.content}`,
                };
            },
        },
        methods: {
            handleSuccess(response) {
                this.$events.emit('fileWasUploaded', response);
            },
            uploadFile(event) {
                this.fileIsUploading = true;
                const data = new FormData();
                data.append('parent_id', this.parentId);
                data.append('file', event.file);
                axios.post(route('api.media.store'), data)
                    .then((response) => {
                        this.$events.emit('fileWasUploaded', response);
                        this.fileIsUploading = false;
                    })
                    .catch((error) => {
                        console.log(error.response.data);
                        this.fileIsUploading = false;
                        this.$notify.error({
                            title: 'Error',
                            message: error.response.data.errors.file[0],
                        });
                    });
            },
            handleRemove() {},
        },
        mounted() {
        },
    };
</script>
<style>
    .el-upload__input {
        display: none !important;
    }
    .el-upload--text {
        display: block;
    }
    .el-upload-dragger {
        width: 100%;
    }
    .media-upload {
        margin-bottom: 10px;
    }
</style>
