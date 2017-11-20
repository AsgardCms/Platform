<template>
    <div>
        <p class="pull-right">
            <el-button type="text"
                       @click="changeStateForAll(1)">
                {{ trans('roles.allow all') }}
            </el-button>
            <el-button type="text"
                       :disabled="isRole"
                       @click="changeStateForAll(0)">
                {{ trans('roles.inherit all') }}
            </el-button>
            <el-button type="text"
                       @click="changeStateForAll(-1)">
                {{ trans('roles.deny all') }}
            </el-button>
        </p>
        <div v-for="(value, name) in allPermissions" :key="name">
            <h3>{{ name }}</h3>
            <div v-for="(permissionActions, subPermissionTitle) in value" :key="subPermissionTitle">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3"><h4 class="pull-left">{{ ucfirst(subPermissionTitle) }}</h4></div>
                            <div class="col-md-9">
                                <p  style="margin-top: 10px;">
                                    <el-button type="text"
                                               @click="changeState(subPermissionTitle, permissionActions, 1)">
                                        {{ trans('roles.allow all') }}
                                    </el-button>
                                    <el-button type="text"
                                               :disabled="isRole"
                                               @click="changeState(subPermissionTitle, permissionActions, 0)">
                                        {{ trans('roles.inherit all') }}
                                    </el-button>
                                    <el-button type="text"
                                               @click="changeState(subPermissionTitle, permissionActions, -1)">
                                        {{ trans('roles.deny all') }}
                                    </el-button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" v-for="(label, permissionAction) in permissionActions" :key="permissionAction">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="visible-sm-block visible-md-block visible-lg-block">
                                    <label class="control-label text-right" style="display: block">{{ parseTranslation(label) }}</label>
                                </div>
                                <div class="visible-xs-block">
                                    <label class="control-label">{{ parseTranslation(label) }}</label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <el-radio-group v-model="permissions[`${subPermissionTitle}.${permissionAction}`]">
                                    <el-radio-button :label="1" @click="triggerEvent">{{ trans('roles.allow') }}</el-radio-button>
                                    <el-radio-button :label="0" @click="triggerEvent" :disabled="isRole">{{ trans('roles.inherit') }}</el-radio-button>
                                    <el-radio-button :label="-1" @click="triggerEvent">{{ trans('roles.deny') }}</el-radio-button>
                                </el-radio-group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import StringHelpers from '../../../../Core/Assets/js/mixins/StringHelpers.vue';

    export default {
        mixins: [StringHelpers],
        props: {
            isRole: { type: Boolean },
            currentPermissions: { default: null },
        },
        data() {
            return {
                permissions: {},
                allPermissions: {},
            };
        },
        methods: {
            triggerEvent() {
                this.$emit('input', this.permissions);
            },
            parseTranslation(label) {
                return this.trans(label.split('::')[1]);
            },
            getPermissionKey(subPermissionTitle, permissionAction) {
                return `${subPermissionTitle}.${permissionAction}`;
            },
            changeState(permissionPart, actions, state) {
                _.forEach(actions, (translationKey, key) => {
                    this.permissions[`${permissionPart}.${key}`] = state;
                });
            },
            changeStateForAll(state) {
                _.forEach(this.permissions, (index, permission) => {
                    this.permissions[permission] = state;
                });
            },
            fetchPermissions() {
                axios.get(route('api.user.permissions.index'))
                    .then((response) => {
                        this.loading = false;
                        this.allPermissions = response.data.permissions;
                    });
            },
        },
        watch: {
            currentPermissions() {
                if (this.currentPermissions !== null) {
                    this.permissions = this.currentPermissions;
                }
            },
        },
        mounted() {
            this.fetchPermissions();
        },
    };
</script>
