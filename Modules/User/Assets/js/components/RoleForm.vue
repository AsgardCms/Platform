<template>
    <div>
        <div class="content-header">
            <h1>
                {{ trans(`roles.${pageTitle}`) }}
                <small>{{ role.name }}</small>
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">{{ trans('core.breadcrumb.home') }}</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.role.index'}">
                    {{ trans('roles.title.roles') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.user.role.create'}">
                    {{ trans(`roles.${pageTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-form
            ref="form"
            v-loading.body="loading"
            :model="role"
            label-width="120px"
            label-position="top"
            @keydown="form.errors.clear($event.target.name)"
        >
            <form-errors :form="form"></form-errors>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs>
                                <el-tab-pane :label="trans('roles.tabs.data')">
                                    <el-form-item :label="trans('roles.form.name')" :class="{'el-form-item is-error': form.errors.has('name') }">
                                        <el-input v-model="role.name" @input="generateSlug"></el-input>
                                        <div v-if="form.errors.has('name')" class="el-form-item__error" v-text="form.errors.first('name')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('roles.form.slug')" :class="{'el-form-item is-error': form.errors.has('slug') }">
                                        <el-input v-model="role.slug">
                                            <el-button slot="prepend" @click="generateSlug">Generate</el-button>
                                        </el-input>
                                        <div v-if="form.errors.has('slug')" class="el-form-item__error" v-text="form.errors.first('slug')"></div>
                                    </el-form-item>
                                </el-tab-pane>
                                <el-tab-pane :label="trans('roles.tabs.permissions')">
                                    <asgard-permissions v-model="role.permissions" :current-permissions="role.permissions" is-role></asgard-permissions>
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
                                <el-button :loading="loading" type="primary" @click="onSubmit()">
                                    {{ trans('core.save') }}
                                </el-button>
                                <el-button @click="onCancel()">
                                    {{ trans('core.button.cancel') }}
                                </el-button>
                            </el-form-item>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>
        <button v-show="false" v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.user.role.index'})"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import FormErrors from '../../../../Core/Assets/js/components/FormErrors.vue';
    import Slugify from '../../../../Core/Assets/js/mixins/Slugify';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import AsgardPermissions from './AsgardPermissions.vue';

    export default {
        components: { AsgardPermissions, FormErrors },
        mixins: [Slugify, ShortcutHelper],
        props: {
            locales: { default: null, type: Object },
            pageTitle: { default: null, type: String },
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
        mounted() {
            this.fetchRole();
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
                        this.pushRoute({ name: 'admin.user.role.index' });
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
                this.pushRoute({ name: 'admin.user.role.index' });
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
    };
</script>
