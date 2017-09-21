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

<template>
    <el-upload
            class="upload-demo"
            :action="uploadUrl"
            :on-remove="handleRemove"
            :on-success="handleSuccess"
            :file-list="fileList"
            :headers="requestHeaders"
            style="display: inline-block; margin-right: 10px;">
        <el-button size="small" type="primary" style="padding: 11px 9px;">Upload File</el-button>
    </el-upload>
</template>

<script>
    export default {
        data() {
            return {
                fileList: [],
            }
        },
        computed: {
            uploadUrl() {
                return route('api.media.store').domain + route('api.media.store').url;
            },
            requestHeaders() {
                let userApiToken = document.head.querySelector('meta[name="user-api-token"]');
                return {
                    'Authorization': 'Bearer ' + userApiToken.content
                };
            },
        },
        methods: {
            handleSuccess(response, file, fileList) {
                this.$events.emit('fileWasUploaded', response);
                this.fileList = [];
            },
            handleRemove() {},
        },
        mounted() {
        }
    }
</script>
