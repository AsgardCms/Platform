<template>
    <el-form ref="form" :model="page" label-width="120px" label-position="top">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-body">
                    <el-tabs type="card">
                            <el-tab-pane :label="localeArray.name" v-for="(localeArray, locale) in locales" :key="localeArray.name">

                                <el-form-item :label="translate('page', 'title')">
                                    <el-input v-model="page[locale].title"></el-input>
                                </el-form-item>
                                <el-form-item :label="translate('page', 'slug')">
                                    <el-input v-model="page[locale].slug"></el-input>
                                </el-form-item>

                                <el-form-item :label="translate('page', 'body')">
                                    <ckeditor v-model="page[locale].body">
                                    </ckeditor>
                                </el-form-item>

                                <div class="panel box box-primary">
                                    <div class="box-header">
                                        <h4 class="box-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" :href="['#collapseMeta-'] + locale">
                                                {{ translate('page', 'meta_data') }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div style="height: 0px;" :id="['collapseMeta-'] + locale" class="panel-collapse collapse">
                                        <div class="box-body">
                                            <el-form-item :label="translate('page', 'meta_title')">
                                                <el-input v-model="page[locale].meta_title"></el-input>
                                            </el-form-item>
                                            <el-form-item :label="translate('page', 'meta_description')">
                                                <el-input type="textarea" v-model="page[locale].meta_description"></el-input>
                                            </el-form-item>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel box box-primary">
                                    <div class="box-header">
                                        <h4 class="box-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" :href="['#collapseFacebook-'] + locale">
                                                {{ translate('page', 'facebook_data') }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div style="height: 0px;" :id="['collapseFacebook-'] + locale" class="panel-collapse collapse">
                                        <div class="box-body">
                                            <el-form-item :label="translate('page', 'og_title')">
                                                <el-input v-model="page[locale].og_title"></el-input>
                                            </el-form-item>
                                            <el-form-item :label="translate('page', 'og_description')">
                                                <el-input type="textarea" v-model="page[locale].og_description"></el-input>
                                            </el-form-item>
                                            <el-form-item :label="translate('page', 'og_type')">
                                                <el-select v-model="ogType" :placeholder="translate('page', 'og_type')">
                                                    <el-option :label="translate('page', 'facebook-types.website')" value="website"></el-option>
                                                    <el-option :label="translate('page', 'facebook-types.product')" value="product"></el-option>
                                                    <el-option :label="translate('page', 'facebook-types.article')" value="article"></el-option>
                                                </el-select>
                                            </el-form-item>
                                        </div>
                                    </div>
                                </div>

                                <el-form-item>
                                    <el-button type="primary" @click="onSubmit()">{{ translate('core', 'button.create') }}</el-button>
                                    <el-button @click="onCancel()">{{ translate('core', 'button.cancel') }}</el-button>
                                </el-form-item>

                            </el-tab-pane>
                        </el-tabs>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="box box-primary">
                <div class="box-body">
                    <el-form-item label="">
                        <el-checkbox v-model="page.is_home" :true-label="1" :false-label="0" name="is_home" :label="translate('page', 'is homepage')"></el-checkbox>
                    </el-form-item>
                    <el-form-item :label="translate('page', 'template')">
                        <el-select v-model="template" filterable>
                            <el-option v-for="(template, key) in templates" :key="template"
                                    :label="template" :value="key"></el-option>
                        </el-select>
                    </el-form-item>
                </div>
            </div>
        </div>
    </div>
    </el-form>
</template>

<script>
    import axios from 'axios'
    import Translate from '../../../../Core/Assets/js/mixins/Translate'

    export default {
        mixins: [Translate],
        props: {
            locales: {default: null}
        },
        watch: {
            ogType: function () {
                this.page.og_type = this.ogType;
            },
            template: function () {
                this.page.template = this.template;
            },
        },
        data() {
            return {
                page: _.fromPairs(_.keys(this.locales).map(locale => [locale, {}])),
                ogType: '',
                template: '',
                templates: {
                    'index': 'index',
                    'home': 'home',
                },
            }
        },
        methods: {
            onSubmit() {
                axios.post(route('api.page.page.store'), this.page)
                    .then(response => {
                        console.log(response);
                    })
            },
            setPageTypes() {
                axios.get(route('api.page.page-templates.index'))
                    .then(response => {
                        this.templates = response.data;
                    });
            }
        },
        mounted() {
            this.setPageTypes();
        }
    }
</script>
