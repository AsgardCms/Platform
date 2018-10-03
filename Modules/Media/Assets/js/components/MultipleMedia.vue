<template>
    <div>
        <label class="el-form-item__label">{{ getFieldLabel() }}</label>
        <div class="jsThumbnailImageWrapper jsSingleThumbnailWrapper" v-if="hasSelectedMedia">
            <figure v-for="media in this.selectedMedia" :key="media.id">
                <img :src="media.small_thumb" alt="" v-if="media.is_image"/>
                <i :class="`fa ${media.fa_icon}`" style="font-size: 60px;" v-if="! media.is_image"></i>
                <span v-if="! media.is_image" style="display:block;">{{ media.filename }}</span>
                <span class="el-icon-error remove-media" @click="unSelectMedia(media.id)"></span>
            </figure>
            <div class="clearfix"></div>
        </div>
        <div>
            <el-button type="button" @click="dialogVisible = true">{{ trans('media.Browse') }}</el-button>
        </div>
        <el-dialog
            :visible.sync="dialogVisible"
            width="75%"
            :before-close="handleClose">

            <media-list single-modal :event-name="this.eventName"></media-list>

            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ trans('core.button.cancel') }}</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import UploadZone from './UploadZone.vue';
    import MediaList from './MediaList.vue';
    import StringHelpers from '../../../../Core/Assets/js/mixins/StringHelpers.vue';
    import RandomString from '../mixins/RandomString';

    export default {
        mixins: [StringHelpers, RandomString],
        props: {
            zone: { type: String, required: true },
            entity: { type: String, required: true },
            entityId: { default: null },
            label: { type: String },
        },
        components: {
            'upload-zone': UploadZone,
            'media-list': MediaList,
        },
        watch: {
            entityId() {
                if (this.entityId) {
                    this.fetchMedia();
                }
            },
        },
        data() {
            return {
                dialogVisible: false,
                selectedMedia: [],
                eventName: '',
            };
        },
        computed: {
            hasSelectedMedia() {
                return this.selectedMedia !== undefined && !_.isEmpty(this.selectedMedia);
            },
        },
        methods: {
            handleClose(done) {
                done();
            },
            unSelectMedia(id) {
                this.selectedMedia = _.reject(this.selectedMedia, media => media.id === id);
                this.$emit('file-unselected', { id, zone: this.zone });
            },
            fetchMedia() {
                axios.get(route('api.media.get-by-zone-and-entity', {
                        zone: this.zone,
                        entity: this.entity,
                        entity_id: this.entityId,
                    }))
                    .then((response) => {
                        this.selectedMedia = response.data.data;
                        _.forEach(this.selectedMedia, (file) => {
                            this.$emit('multiple-file-selected', _.merge(file, { zone: this.zone }));
                        });
                    });
            },
            getFieldLabel() {
                return this.label || this.ucwords(this.zone.replace('_', ' '));
            },
        },
        mounted() {
            if (this.entityId) {
                this.fetchMedia();
            }
            this.eventName = `file-was-selected${this.randomString()}${Math.floor(Math.random() * 999999)}`;

            this.$events.listen(this.eventName, (mediaData) => {
                if (_.find(this.selectedMedia, mediaData) === undefined) {
                    if (!this.selectedMedia) this.selectedMedia = [];
                    this.selectedMedia.push(mediaData);
                    this.$emit('multiple-file-selected', _.merge(mediaData, { zone: this.zone }));
                }
            });
        },
    };
</script>
<style>
    .remove-media {
        position: absolute;
        top: 5px;
        left: 5px;
        color: #FA5555;
    }
</style>
