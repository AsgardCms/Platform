<template>
    <div class="row">
        <div class="col-xs-12">
            <el-upload
                    class="media-upload"
                    drag
                    :action="uploadUrl"
                    :on-preview="handlePreview"
                    :on-remove="handleRemove"
                    :on-success="handleSuccess"
                    :file-list="fileList"
                    :headers="requestHeaders"
                    multiple>
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
            </el-upload>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fileList: [],
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
                this.fileList = [];
            },
            handlePreview() {},
            handleRemove() {},
        },
        mounted() {},
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
