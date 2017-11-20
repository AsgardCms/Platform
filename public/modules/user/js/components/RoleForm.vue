<template>
    <div>
        <div class="content-header">
            <h1>
                {{ trans(`roles.${pageTitle}`) }} <small>{{ role.name }}</small>
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">{{ trans('core.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.roles.index'}">{{ trans('roles.title.roles') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.roles.create'}">{{ trans(`roles.${pageTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-form ref="form"
                 :model="role"
                 label-width="120px"
                 label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs>
                                <el-tab-pane :label="trans('roles.tabs.data')">
                                    <el-form-item :label="trans('roles.form.name')"
                                                  :class="{'el-form-item is-error': form.errors.has('name') }">
                                        <el-input v-model="role.name"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has('name')"
                                             v-text="form.errors.first('name')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('roles.form.slug')"
                                                  :class="{'el-form-item is-error': form.errors.has('slug') }">
                                        <el-input v-model="role.slug">
                                            <el-button slot-scope="prepend" @click="generateSlug">Generate</el-button>
                                        </el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has('slug')"
                                             v-text="form.errors.first('slug')"></div>
                                    </el-form-item>
                                </el-tab-pane>
                                <el-tab-pane :label="trans('roles.tabs.permissions')">
                                    <asgard-permissions v-model="role.permissions"
                                                        is-role
                                                        :current-permissions="role.permissions"></asgard-permissions>
                                </el-tab-pane>
                                <el-tab-pane :label="trans('users.title.users')">
                                    <h3>{{ trans('roles.title.users-with-roles') }}</h3>
                                    <ul>
                                        <li v-for="user in role.users" :key="user.id">{{ user.fullname }} ({{ user.email }})</li>
                                    </ul>
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
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.user.roles.index'})" v-show="false"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import Slugify from '../../../../Core/Assets/js/mixins/Slugify';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import AsgardPermissions from './AsgardPermissions.vue';

    export default {
        mixins: [Slugify, ShortcutHelper],
        components: {
            'asgard-permissions': AsgardPermissions,
        },
        props: {
            locales: { default: null },
            pageTitle: { default: null, String },
        },
        data() {
            return {
                role: {
                    name: '',
                    slug: '',
                    permissions: {},
                    users: {},
                },
                form: new Form(),
                loading: false,
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(this.role);
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({ name: 'admin.user.roles.index' });
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
                this.$router.push({ name: 'admin.user.roles.index' });
            },
            generateSlug() {
                this.role.slug = this.slugify(this.role.name);
            },
            fetchRole() {
                this.loading = true;
                let routeUri = '';
                if (this.$route.params.roleId !== undefined) {
                    routeUri = route('api.user.role.find', { role: this.$route.params.roleId });
                } else {
                    routeUri = route('api.user.role.find-new');
                }
                axios.post(routeUri)
                    .then((response) => {
                        this.loading = false;
                        this.role = response.data.data;
                    });
            },
            getRoute() {
                if (this.$route.params.roleId !== undefined) {
                    return route('api.user.role.update', { role: this.$route.params.roleId });
                }
                return route('api.user.role.store');
            },
        },
        mounted() {
            this.fetchRole();
        },
    };
</script>
