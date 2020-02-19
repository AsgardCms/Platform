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
                <el-breadcrumb-item :to="{name: 'admin.page.page.index'}">
                    {{ trans('pages.pages') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.page.page.create'}">
                    {{ trans(`pages.${pageTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form
            ref="form"
            :model="page"
            label-width="120px"
            label-position="top"
            @keydown="form.errors.clear($event.target.name)"
        >
            <form-errors :form="form"></form-errors>
            <div class="row">
                <div class="col-md-10">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs v-model="activeTab">
                                <el-tab-pane v-for="(localeArray, locale) in locales" :key="localeArray.name" :label="localeArray.name" :name="locale">
                                    <span slot="label" :class="{'error' : form.errors.has(locale)}">{{ localeArray.name }}</span>
                                    <el-form-item :label="trans('pages.title')" :class="{'el-form-item is-error': form.errors.has(locale + '.title') }">
                                        <el-input v-model="page[locale].title" @input="generateSlug($event, locale)"></el-input>
                                        <div v-if="form.errors.has(locale + '.title')" class="el-form-item__error" v-text="form.errors.first(locale + '.title')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('pages.slug')" :class="{'el-form-item is-error': form.errors.has(locale + '.slug') }">
                                        <el-input v-model="page[locale].slug">
                                            <el-button slot="prepend" @click="generateSlug($event, locale)">Generate</el-button>
                                        </el-input>
                                        <div v-if="form.errors.has(locale + '.slug')" class="el-form-item__error" v-text="form.errors.first(locale + '.slug')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('pages.body')" :class="{'el-form-item is-error': form.errors.has(locale + '.body') }">
                                        <component :is="getCurrentEditor()" v-model="page[locale].body" :value="page[locale].body"></component>
                                        <div v-if="form.errors.has(locale + '.body')" class="el-form-item__error" v-text="form.errors.first(locale + '.body')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('pages.status')" :class="{'el-form-item is-error': form.errors.has(locale + '.status') }">
                                        <el-switch
                                            v-model="page[locale].status"
                                            active-color="#13ce66"
                                            inactive-color="#ff4949">
                                        </el-switch>
                                        <div v-if="form.errors.has(locale + '.status')" class="el-form-item__error" v-text="form.errors.first(locale + '.status')"></div>
                                    </el-form-item>

                                    <div class="panel box box-primary">
                                        <div class="box-header">
                                            <h4 class="box-title">
                                                <a :href="`#collapseMeta-${locale}`" class="collapsed" data-toggle="collapse" data-parent="#accordion">
                                                    {{ trans('pages.meta_data') }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div :id="`collapseMeta-${locale}`" style="height: 0;" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <el-form-item :label="trans('pages.meta_title')">
                                                    <el-input v-model="page[locale].meta_title"></el-input>
                                                </el-form-item>
                                                <el-form-item :label="trans('pages.meta_description')">
                                                    <el-input v-model="page[locale].meta_description" type="textarea" autosize maxlength="186"></el-input>
                                                </el-form-item>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel box box-primary">
                                        <div class="box-header">
                                            <h4 class="box-title">
                                                <a :href="`#collapseFacebook-${locale}`" class="collapsed" data-toggle="collapse" data-parent="#accordion">
                                                    {{ trans('pages.facebook_data') }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div :id="`collapseFacebook-${locale}`" style="height: 0;" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <el-form-item :label="trans('pages.og_title')">
                                                    <el-input v-model="page[locale].og_title"></el-input>
                                                </el-form-item>
                                                <el-form-item :label="trans('pages.og_description')">
                                                    <el-input v-model="page[locale].og_description" type="textarea" autosize maxlength="186"></el-input>
                                                </el-form-item>
                                                <el-form-item :label="trans('pages.og_type')">
                                                    <el-select v-model="page[locale].og_type" :placeholder="trans('pages.og_type')">
                                                        <el-option :label="trans('pages.facebook-types.website')" value="website"></el-option>
                                                        <el-option :label="trans('pages.facebook-types.product')" value="product"></el-option>
                                                        <el-option :label="trans('pages.facebook-types.article')" value="article"></el-option>
                                                    </el-select>
                                                </el-form-item>
                                            </div>
                                        </div>
                                    </div>

                                    <el-form-item>
                                        <el-button :loading="loading" type="primary" @click="onSubmit()">
                                            {{ trans('core.save') }}
                                        </el-button>
                                        <el-button @click="onCancel()">
                                            {{ trans('core.button.cancel') }}
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
                                <el-checkbox
                                    v-model="page.is_home"
                                    :label="trans('pages.is homepage')"
                                    :true-label="1"
                                    :false-label="0"
                                    name="is_home"
                                ></el-checkbox>
                            </el-form-item>
                            <el-form-item :label="trans('pages.template')" :class="{'el-form-item is-error': form.errors.has('template') }">
                                <el-select v-model="page.template" filterable>
                                    <el-option v-for="(template, key) in templates" :key="template" :label="template" :value="key"></el-option>
                                </el-select>
                                <div v-if="form.errors.has('template')" class="el-form-item__error" v-text="form.errors.first('template')"></div>
                            </el-form-item>
                            <tags-input v-model="page.tags" :current-tags="page.tags" namespace="asgardcms/page"></tags-input>
                            <single-media
                                :entity-id="page.id"
                                zone="image"
                                entity="Modules\Page\Entities\Page"
                                @single-file-selected="selectSingleFile($event, 'page')"
                            ></single-media>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>
        <button v-show="false" v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.page.page.index'})"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import FormErrors from '../../../../Core/Assets/js/components/FormErrors.vue';
    import Slugify from '../../../../Core/Assets/js/mixins/Slugify';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import ActiveEditor from '../../../../Core/Assets/js/mixins/ActiveEditor';
    import SingleMedia from '../../../../Media/Assets/js/components/SingleMedia.vue';
    import SingleFileSelector from '../../../../Media/Assets/js/mixins/SingleFileSelector';
    import TagsInput from '../../../../Tag/Assets/js/components/TagInput.vue';

    export default {
        components: { FormErrors, SingleMedia, TagsInput },
        mixins: [Slugify, ShortcutHelper, ActiveEditor, SingleFileSelector],
        props: {
            locales: { default: null, type: Object },
            pageTitle: { default: null, type: String },
        },
        data() {
            return {
                page: window._(this.locales)
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
                        status: false
                    }])
                    .fromPairs()
                    .merge({ id: null, template: 'default', is_home: 0, tags: [], urls: {} })
                    .value(),
                templates: {
                    index: 'index',
                    home: 'home',
                    default: 'default',
                },
                form: new Form(),
                loading: false,
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
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
        methods: {
            onSubmit() {
                this.form = new Form(this.page);
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.pushRoute({ name: 'admin.page.page.index' });
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
                this.pushRoute({ name: 'admin.page.page.index' });
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
    };
</script>
