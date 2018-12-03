<template>
    <el-upload
        :action="uploadUrl"
        :show-file-list="false"
        :http-request="uploadFile"
        list-type="picture"
        drag
        multiple
        class="media-upload"
    >
        <i class="el-icon-upload"></i>
        <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
    </el-upload>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            parentId: { default: null, type: Number },
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
        },
        methods: {
            uploadFile(event) {
                this.fileIsUploading = true;
                const data = new FormData();
                data.append('parent_id', this.parentId);
                data.append('file', event.file);
                axios.post(route('api.media.store'), data).then((response) => {
                    this.$events.emit('fileWasUploaded', response);
                    this.$message({
                        type: 'success',
                        message: this.trans('media.file uploaded'),
                    });
                    this.fileIsUploading = false;
                }, (error) => {
                    console.log(error.response.data);
                    this.fileIsUploading = false;
                    this.$notify.error({
                        title: 'Error',
                        message: error.response.data.errors.file[0],
                    });
                });
            },
        },
    };
</script>

<style>
    .media-upload {
        margin-bottom: 10px;
    }

    .el-upload__input {
        display: none !important;
    }

    .el-upload--picture, .el-upload--picture-card {
        width: 100%;
        height: 175px;
        line-height: 100px;
        border: none;
    }

    .el-upload-dragger {
        width: 100%;
        height: 100%;
    }

    .el-upload-dragger .el-upload__text {
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .el-upload--text {
        display: block;
    }
</style>
