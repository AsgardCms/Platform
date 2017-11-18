<template>
    <div class="div">
        <div class="content-header">
            <h1>
                {{ trans(`pages.${pageTitle}`) }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.page.page.index'}">{{ trans('pages.pages') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.page.page.create'}">{{ trans(`pages.${pageTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form ref="form" :model="page" label-width="120px" label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <div class="row">
                <div class="col-md-10">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs v-model="activeTab">
                                <el-tab-pane :label="localeArray.name" v-for="(localeArray, locale) in locales"
                                             :key="localeArray.name" :name="locale">
                                <span slot="label" :class="{'error' : form.errors.has(locale)}">{{ localeArray.name
                                    }}</span>
                                    <el-form-item :label="trans('pages.title')"
                                                  :class="{'el-form-item is-error': form.errors.has(locale + '.title') }">
                                        <el-input v-model="page[locale].title"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.title')"
                                             v-text="form.errors.first(locale + '.title')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('pages.slug')"
                                                  :class="{'el-form-item is-error': form.errors.has(locale + '.slug') }">
                                        <el-input v-model="page[locale].slug">
                                            <el-button slot="prepend" @click="generateSlug($event, locale)">Generate</el-button>
                                        </el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.slug')"
                                             v-text="form.errors.first(locale + '.slug')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('pages.body')"
                                                  :class="{'el-form-item is-error': form.errors.has(locale + '.body') }">
                                        <component :is="getCurrentEditor()" v-model="page[locale].body" :value="page[locale].body">
                                        </component>

                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.body')"
                                             v-text="form.errors.first(locale + '.body')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('pages.status')"
                                                  :class="{'el-form-item is-error': form.errors.has(locale + '.status') }">
                                        <el-checkbox v-model="page[locale].status">{{ trans('pages.status') }}</el-checkbox>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.status')"
                                             v-text="form.errors.first(locale + '.status')"></div>
                                    </el-form-item>

                                    <div class="panel box box-primary">
                                        <div class="box-header">
                                            <h4 class="box-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                                   :href="`#collapseMeta-${locale}`">
                                                    {{ trans('pages.meta_data') }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div style="height: 0px;" :id="`collapseMeta-${locale}`"
                                             class="panel-collapse collapse">
                                            <div class="box-body">
                                                <el-form-item :label="trans('pages.meta_title')">
                                                    <el-input v-model="page[locale].meta_title"></el-input>
                                                </el-form-item>
                                                <el-form-item :label="trans('pages.meta_description')">
                                                    <el-input type="textarea"
                                                              v-model="page[locale].meta_description"></el-input>
                                                </el-form-item>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel box box-primary">
                                        <div class="box-header">
                                            <h4 class="box-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                                   :href="`#collapseFacebook-${locale}`">
                                                    {{ trans('pages.facebook_data') }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div style="height: 0px;" :id="`collapseFacebook-${locale}`"
                                             class="panel-collapse collapse">
                                            <div class="box-body">
                                                <el-form-item :label="trans('pages.og_title')">
                                                    <el-input v-model="page[locale].og_title"></el-input>
                                                </el-form-item>
                                                <el-form-item :label="trans('pages.og_description')">
                                                    <el-input type="textarea"
                                                              v-model="page[locale].og_description"></el-input>
                                                </el-form-item>
                                                <el-form-item :label="trans('pages.og_type')">
                                                    <el-select v-model="page[locale].og_type"
                                                               :placeholder="trans('pages.og_type')">
                                                        <el-option :label="trans('pages.facebook-types.website')"
                                                                   value="website"></el-option>
                                                        <el-option :label="trans('pages.facebook-types.product')"
                                                                   value="product"></el-option>
                                                        <el-option :label="trans('pages.facebook-types.article')"
                                                                   value="article"></el-option>
                                                    </el-select>
                                                </el-form-item>
                                            </div>
                                        </div>
                                    </div>

                                    <el-form-item>
                                        <el-button type="primary" @click="onSubmit()" :loading="loading">
                                            {{ trans('core.save') }}
                                        </el-button>
                                        <el-button @click="onCancel()">{{ trans('core.button.cancel') }}
                                        </el-button>
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
                                <el-checkbox v-model="page.is_home" :true-label="1" :false-label="0" name="is_home"
                                             :label="trans('pages.is homepage')"></el-checkbox>
                            </el-form-item>
                            <el-form-item :label="trans('pages.template')"
                                          :class="{'el-form-item is-error': form.errors.has('template') }">
                                <el-select v-model="page.template" filterable>
                                    <el-option v-for="(template, key) in templates" :key="template"
                                               :label="template" :value="key"></el-option>
                                </el-select>
                                <div class="el-form-item__error" v-if="form.errors.has('template')"
                                     v-text="form.errors.first('template')"></div>
                            </el-form-item>
                            <tags-input namespace="asgardcms/page" v-model="tags" :current-tags="tags"></tags-input>

                            <single-media zone="image" @singleFileSelected="selectSingleFile($event, 'page')"
                                          entity="Modules\Page\Entities\Page" :entity-id="$route.params.pageId"></single-media>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.page.page.index'})" v-show="false"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import Slugify from '../../../../Core/Assets/js/mixins/Slugify';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import ActiveEditor from '../../../../Core/Assets/js/mixins/ActiveEditor';
    import SingleFileSelector from '../../../../Media/Assets/js/mixins/SingleFileSelector';

    export default {
        mixins: [Slugify, ShortcutHelper, ActiveEditor, SingleFileSelector],
        props: {
            locales: { default: null },
            pageTitle: { default: null, String },
        },
        data() {
            return {
                page: _(this.locales)
                    .keys()
                    .map(locale => [locale, {
                        title: '',
                        slug: '',
                        body: '',
                        meta_title: '',
                        meta_description: '',
                        og_title: '',
                        og_description: '',
                        og_type: '',
                    }])
                    .fromPairs()
                    .merge({ template: 'default', is_home: 0, medias_single: [] })
                    .value(),

                templates: {
                    index: 'index',
                    home: 'home',
                    default: 'default',
                },
                form: new Form(),
                loading: false,
                tags: {},
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.page, { tags: this.tags }));
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({ name: 'admin.page.page.index' });
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
                this.$router.push({ name: 'admin.page.page.index' });
            },
            fetchTemplates() {
                axios.get(route('api.page.page-templates.index'))
                    .then((response) => {
                        this.templates = response.data;
                    });
            },
            generateSlug(event, locale) {
                this.page[locale].slug = this.slugify(this.page[locale].title);
            },
            fetchPage() {
                this.loading = true;
                axios.post(route('api.page.page.find', { page: this.$route.params.pageId }))
                    .then((response) => {
                        this.loading = false;
                        this.page = response.data.data;
                        this.tags = response.data.data.tags;
                        $('.publicUrl').attr('href', this.page.urls.public_url).show();
                    });
            },
            getRoute() {
                if (this.$route.params.pageId !== undefined) {
                    return route('api.page.page.update', { page: this.$route.params.pageId });
                }
                return route('api.page.page.store');
            },
        },
        mounted() {
            this.fetchTemplates();

            if (this.$route.params.pageId !== undefined) {
                this.fetchPage();
            }
        },
        destroyed() {
            $('.publicUrl').hide();
        },
    };
</script>
