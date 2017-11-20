<template>
    <div>
        <div class="content-header">
            <h1>
                {{ trans(`users.${pageTitle}`) }} <small>{{ user.first_name }} {{ user.last_name }}</small>
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">{{ trans('core.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.users.index'}">{{ trans('users.title.users') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.users.create'}">{{ trans(`users.${pageTitle}`) }}
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
                                    <el-form-item :label="trans('users.form.is activated')"
                                                  :class="{'el-form-item is-error': form.errors.has('activated') }">
                                        <el-checkbox v-model="user.activated">Activated</el-checkbox>
                                        <div class="el-form-item__error" v-if="form.errors.has('activated')"
                                             v-text="form.errors.first('activated')"></div>
                                    </el-form-item>
                                    <div v-if="user.is_new">
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
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane :label="trans('users.tabs.roles')">
                                    <el-form-item :label="trans('users.form.password')"
                                                  :class="{'el-form-item is-error': form.errors.has('password') }">
                                        <el-select v-model="user.roles" multiple placeholder="Select">
                                            <el-option
                                                    v-for="role in roles"
                                                    :key="role.id"
                                                    :label="role.name"
                                                    :value="role.id">
                                            </el-option>
                                        </el-select>
                                        <div class="el-form-item__error" v-if="form.errors.has('password')"
                                             v-text="form.errors.first('password')"></div>
                                    </el-form-item>
                                </el-tab-pane>
                                <el-tab-pane :label="trans('users.tabs.permissions')">
                                    <asgard-permissions v-model="user.permissions"
                                                        :current-permissions="user.permissions"></asgard-permissions>
                                </el-tab-pane>
                                <el-tab-pane :label="trans('users.tabs.new password')" v-if="! user.is_new">
                                    <div v-if="! user.is_new">
                                        <div class="col-md-6">
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
                                        </div>
                                        <div class="col-md-6">
                                            <h4>{{ trans('users.tabs.or send reset password mail') }}</h4>
                                            <el-button type="info"
                                                       :loading="resetEmailIsLoading"
                                                       @click="sendResetEmail">
                                                {{ trans('users.send reset password email') }}
                                            </el-button>
                                        </div>
                                    </div>
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                        <div class="box-footer">
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit()" :loading="loading">
                                    {{ trans('core.save') }}
                                </el-button>
                                <el-button @click="onCancel()">{{ trans('core.button.cancel') }}
                                </el-button>
                            </el-form-item>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.user.users.index'})" v-show="false"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import AsgardPermissions from './AsgardPermissions.vue';

    export default {
        mixins: [ShortcutHelper],
        components: {
            'asgard-permissions': AsgardPermissions,
        },
        props: {
            locales: { default: null },
            pageTitle: { default: null, String },
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
                resetEmailIsLoading: false,
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(this.user);
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({ name: 'admin.user.users.index' });
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
            onCancel() {
                this.$router.push({ name: 'admin.user.users.index' });
            },
            fetchUser() {
                this.loading = true;
                let routeUri = '';
                if (this.$route.params.userId !== undefined) {
                    routeUri = route('api.user.user.find', { user: this.$route.params.userId });
                } else {
                    routeUri = route('api.user.user.find-new');
                }
                axios.post(routeUri)
                    .then((response) => {
                        this.loading = false;
                        this.user = response.data.data;
                    });
            },
            getRoute() {
                if (this.$route.params.userId !== undefined) {
                    return route('api.user.user.update', { user: this.$route.params.userId });
                }
                return route('api.user.user.store');
            },
            fetchRoles() {
                axios.get(route('api.user.role.all'))
                    .then((response) => {
                        this.roles = response.data.data;
                    });
            },
            sendResetEmail() {
                this.resetEmailIsLoading = true;
                axios.get(route('api.user.user.sendResetPassword', { user: this.$route.params.userId }))
                    .then((response) => {
                        this.resetEmailIsLoading = false;
                        this.$notify.success({
                            title: 'Success',
                            message: response.data.message,
                        });
                    });
            },
        },
        mounted() {
            this.fetchUser();
            this.fetchRoles();
        },
    };
</script>
