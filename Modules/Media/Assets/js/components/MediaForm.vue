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
                <el-breadcrumb-item :to="{name: 'admin.media.media.index', query: {folder_id: media.folder_id}}">{{ trans('media.breadcrumb.media') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.media.media.edit'}">{{ trans('media.title.edit media') }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-form ref="form" :model="media" label-width="120px" label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs v-model="activeTab">
                                <el-tab-pane :label="localeArray.name" v-for="(localeArray, locale) in locales"
                                             :key="localeArray.name" :name="locale">

                                    <el-form-item :label="trans('media.form.alt_attribute')"
                                                  :class="{'el-form-item is-error': form.errors.has(locale + '.alt_attribute') }">
                                        <el-input v-model="media[locale].alt_attribute"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.alt_attribute')"
                                             v-text="form.errors.first(locale + '.alt_attribute')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('media.form.description')"
                                                  :class="{'el-form-item is-error': form.errors.has(locale + '.description') }">
                                        <el-input type="textarea" v-model="media[locale].description"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.description')"
                                             v-text="form.errors.first(locale + '.description')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('media.form.keywords')"
                                                  :class="{'el-form-item is-error': form.errors.has(locale + '.keywords') }">
                                        <el-input v-model="media[locale].keywords"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.keywords')"
                                             v-text="form.errors.first(locale + '.keywords')"></div>
                                    </el-form-item>

                                    <hr>

                                    <tags-input namespace="asgardcms/media" v-model="tags" :current-tags="tags"></tags-input>

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
                <div class="col-md-4">
                    <img :src="media.path" alt="" v-if="media.is_image" style="width: 100%;"/>
                    <i class="fa fa-file" style="font-size: 50px;" v-else></i>
                </div>
            </div>
        </el-form>

        <div class="row">
            <div class="col-md-12">
                <h3>Thumbnails</h3>
                <ul class="list-unstyled">
                    <li v-for="thumbnail in media.thumbnails" :key="thumbnail.name" style="float:left; margin-right: 10px">
                        <img :src="thumbnail.path" alt=""/>
                        <p class="text-muted" style="text-align: center">{{ thumbnail.name }} ({{ thumbnail.size }})</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';
    import axios from 'axios';

    export default {
        props: {
            locales: { default: null },
        },
        data() {
            return {
                media: _(this.locales)
                    .keys()
                    .map(locale => [locale, {
                        description: '',
                        alt_attribute: '',
                        keywords: '',
                    }])
                    .fromPairs()
                    // .merge({template: 'default', is_home: 0, medias_single: []})
                    .value(),
                form: new Form(),
                loading: false,
                tags: {},
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        methods: {
            fetchMedia() {
                this.loading = true;
                axios.get(route('api.media.media.find', { media: this.$route.params.mediaId }))
                    .then((response) => {
                        this.loading = false;
                        this.media = response.data.data;
                        this.tags = response.data.data.tags;
                    });
            },
            onSubmit() {
                this.form = new Form(_.merge(this.media, { tags: this.tags }));
                this.loading = true;

                this.form.put(route('api.media.media.update', { file: this.media.id }))
                    .then((response) => {
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({ name: 'admin.media.media.index', query: { folder_id: this.media.folder_id } });
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
                if (this.media.folder_id == 0) {
                    this.$router.push({ name: 'admin.media.media.index', query: {} });
                    return;
                }
                this.$router.push({ name: 'admin.media.media.index', query: { folder_id: this.media.folder_id } });
            },
        },
        mounted() {
            if (this.$route.params.mediaId !== undefined) {
                this.fetchMedia();
            }
        },
    };
</script>
<style>
    .el-select{
        display: block;
    }
</style>
