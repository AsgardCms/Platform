<template>
    <div>
        <div class="content-header">
            <h1>
                {{ trans('users.title.edit-profile') }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">{{ trans('core.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.users.account'}">{{ trans('users.breadcrumb.edit-profile') }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form ref="form"
                 :model="user"
                 label-width="120px"
                 label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs>
                                <el-tab-pane :label="trans('users.tabs.data')">
                                    <span slot="label"
                                          :class="{'error' : form.errors.any()}">
                                        {{ trans('users.tabs.data') }}
                                    </span>
                                    <el-form-item :label="trans('users.form.first-name')"
                                                  :class="{'el-form-item is-error': form.errors.has('first_name') }">
                                        <el-input v-model="user.first_name"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has('first_name')"
                                             v-text="form.errors.first('first_name')"></div>
                                    </el-form-item>
                                    <el-form-item :label="trans('users.form.last-name')"
                                                  :class="{'el-form-item is-error': form.errors.has('last_name') }">
                                        <el-input v-model="user.last_name"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has('last_name')"
                                             v-text="form.errors.first('last_name')"></div>
                                    </el-form-item>
                                    <el-form-item :label="trans('users.form.email')"
                                                  :class="{'el-form-item is-error': form.errors.has('email') }">
                                        <el-input v-model="user.email"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has('email')"
                                             v-text="form.errors.first('email')"></div>
                                    </el-form-item>
                                </el-tab-pane>
                                <el-tab-pane :label="trans('users.tabs.new password')" v-if="! user.is_new">
                                        <h4>{{ trans('users.new password setup') }}</h4>
                                        <el-form-item :label="trans('users.form.password')"
                                                      :class="{'el-form-item is-error': form.errors.has('password') }">
                                            <el-input v-model="user.password"
                                                      type="password"></el-input>
                                            <div class="el-form-item__error" v-if="form.errors.has('password')"
                                                 v-text="form.errors.first('password')"></div>
                                        </el-form-item>
                                        <el-form-item :label="trans('users.form.password-confirmation')"
                                                      :class="{'el-form-item is-error': form.errors.has('password_confirmation') }">
                                            <el-input v-model="user.password_confirmation"
                                                      type="password"></el-input>
                                            <div class="el-form-item__error" v-if="form.errors.has('password_confirmation')"
                                                 v-text="form.errors.first('password_confirmation')"></div>
                                        </el-form-item>
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                        <div class="box-footer">
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit()" :loading="loading">
                                    {{ trans('core.save') }}
                                </el-button>
                            </el-form-item>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';

    export default {
        props: {
            locales: { default: null },
        },
        data() {
            return {
                user: {
                    first_name: '',
                    last_name: '',
                    permissions: {},
                    roles: {},
                    is_new: false,
                },
                roles: {},
                form: new Form(),
                loading: false,
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(this.user);
                this.loading = true;

                this.form.post(route('api.account.profile.update'))
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
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
            fetchUser() {
                this.loading = true;
                axios.get(route('api.account.profile.find-current-user'))
                    .then((response) => {
                        this.loading = false;
                        this.user = response.data.data;
                    });
            },
        },
        mounted() {
            this.fetchUser();
        },
    };
</script>
