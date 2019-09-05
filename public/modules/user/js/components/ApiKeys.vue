<template>
    <div>
        <div class="content-header">
            <h1>
                {{ trans('users.api-keys') }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">{{ trans('core.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.user.account.api-keys'}">
                    {{ trans('users.api-keys') }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('users.your api keys') }}</h3>
                        <div class="box-tools pull-right">
                            <el-button type="primary" size="small" icon="plus" @click="generateKey">
                                {{ trans('users.generate new api key') }}
                            </el-button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul class="list-unstyled">
                            <li v-for="key in apiKeys" :key="key.id" style="margin-bottom: 20px;">
                                <el-input v-model="key.access_token" disabled>
                                    <el-button slot="prepend" @click="destroyApiKey(key)">
                                        <i class="fa fa-times"></i>
                                    </el-button>
                                </el-input>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                loading: false,
                apiKeys: {},
            };
        },
        mounted() {
            this.fetchApiKeys();
        },
        methods: {
            fetchApiKeys() {
                this.loading = true;
                axios.get(route('api.account.api.index'))
                    .then((response) => {
                        this.loading = false;
                        this.apiKeys = response.data.data;
                    });
            },
            generateKey() {
                axios.get(route('api.account.api.create'))
                    .then((response) => {
                        this.loading = false;
                        this.apiKeys = response.data.data;
                        this.$message({
                            type: 'success',
                            message: response.data.message,
                        });
                    });
            },
            destroyApiKey(apiKey) {
                this.$confirm(this.trans('users.delete api key confirm'), '', {
                    confirmButtonText: this.trans('core.button.delete'),
                    cancelButtonText: this.trans('core.button.cancel'),
                    type: 'warning',
                    confirmButtonClass: 'el-button--danger',
                }).then(() => {
                    axios.delete(route('api.account.api.destroy', { userTokenId: apiKey.id }))
                        .then((response) => {
                            this.loading = false;
                            this.apiKeys = response.data.data;
                            this.$message({
                                type: 'success',
                                message: response.data.message,
                            });
                        });
                });
            },
        },
    };
</script>
