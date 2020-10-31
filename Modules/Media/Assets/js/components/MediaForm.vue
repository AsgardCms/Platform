<template>
    <div>
        <div class="content-header">
            <h1>
                {{ trans('media.title.edit media') }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.media.media.index', query: {folder_id: media.folder_id}}">
                    {{ trans('media.breadcrumb.media') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.media.media.edit'}">
                    {{ trans('media.title.edit media') }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-form
            ref="form"
            v-loading.body="loading"
            :model="media"
            label-width="120px"
            label-position="top"
            @keydown="form.errors.clear($event.target.name)"
        >
            <form-errors :form="form"></form-errors>
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs v-model="activeTab">
                                <el-tab-pane v-for="(localeArray, locale) in locales" :key="localeArray.name" :label="localeArray.name" :name="locale">
                                    <span slot="label" :class="{'error' : form.errors.has(locale)}"><i :class="'flag-icon flag-icon-' + locale"></i> &nbsp; {{ localeArray.name }}</span>

                                    <el-form-item :label="trans('media.form.alt_attribute')" :class="{'el-form-item is-error': form.errors.has(locale + '.alt_attribute') }">
                                        <el-input v-model="media[locale].alt_attribute"></el-input>
                                        <div v-if="form.errors.has(locale + '.alt_attribute')" class="el-form-item__error" v-text="form.errors.first(locale + '.alt_attribute')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('media.form.description')" :class="{'el-form-item is-error': form.errors.has(locale + '.description') }">
                                        <el-input v-model="media[locale].description" type="textarea"></el-input>
                                        <div v-if="form.errors.has(locale + '.description')" class="el-form-item__error" v-text="form.errors.first(locale + '.description')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('media.form.keywords')" :class="{'el-form-item is-error': form.errors.has(locale + '.keywords') }">
                                        <el-input v-model="media[locale].keywords"></el-input>
                                        <div v-if="form.errors.has(locale + '.keywords')" class="el-form-item__error" v-text="form.errors.first(locale + '.keywords')"></div>
                                    </el-form-item>

                                    <hr>

                                    <tags-input v-model="media.tags" :current-tags="media.tags" namespace="asgardcms/media"></tags-input>

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
                <div class="col-md-4">
                    <div v-if="media.is_image">
                        <img :src="media.path" alt="" class="img-responsive">
                    </div>
                    <div v-else>
                        <i v-if="media.fa_icon" :class="media.fa_icon" class="fa fa-5x"></i>
                        <i v-else class="fa fa-5x fa-file"></i>
                    </div>
                </div>
            </div>
        </el-form>

        <div class="row">
            <div class="col-md-12">
                <h3>Thumbnails</h3>
                <ul class="list-unstyled">
                    <li v-for="thumbnail in media.thumbnails" :key="thumbnail.name" style="float:left; margin-right: 10px">
                        <img :src="thumbnail.path" alt="">
                        <p class="text-muted" style="text-align: center">{{ thumbnail.name }} ({{ thumbnail.size }})</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import FormErrors from '../../../../Core/Assets/js/components/FormErrors.vue';
    import TagsInput from '../../../../Tag/Assets/js/components/TagInput.vue';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';

    export default {
        components: { FormErrors, TagsInput },
        mixins: [ShortcutHelper],
        props: {
            locales: { default: null, type: Object },
        },
        data() {
            return {
                media: window._(this.locales)
                    .keys()
                    .map(locale => [locale, {
                        description: '',
                        alt_attribute: '',
                        keywords: '',
                    }])
                    .fromPairs()
                    .merge({
                        id: '',
                        filename: '',
                        path: '',
                        is_image: '',
                        is_folder: '',
                        media_type: '',
                        fa_icon: '',
                        created_at: '',
                        folder_id: '',
                        small_thumb: '',
                        medium_thumb: '',
                        tags: [],
                        urls: {},
                        thumbnails: [],
                    })
                    .value(),
                form: new Form(),
                loading: false,
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        mounted() {
            if (this.$route.params.mediaId !== undefined) {
                this.fetchMedia();
            }
        },
        methods: {
            fetchMedia() {
                this.loading = true;
                axios.get(route('api.media.media.find', { media: this.$route.params.mediaId }))
                    .then((response) => {
                        this.loading = false;
                        this.media = response.data.data;
                    });
            },
            onSubmit() {
                this.form = new Form(this.media);
                this.loading = true;

                this.form.put(route('api.media.media.update', { file: this.media.id }))
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.pushRoute({ name: 'admin.media.media.index', query: { folder_id: this.media.folder_id } });
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
                if (this.media.folder_id === 0) {
                    this.pushRoute({ name: 'admin.media.media.index', query: {} });
                    return;
                }
                this.pushRoute({ name: 'admin.media.media.index', query: { folder_id: this.media.folder_id } });
            },
        },
    };
</script>
