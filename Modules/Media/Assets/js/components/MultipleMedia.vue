<template>
    <div>
        <label class="el-form-item__label">{{ getFieldLabel() }}</label>
        <div v-if="hasSelectedMedia" class="jsThumbnailImageWrapper jsSingleThumbnailWrapper">
            <figure v-for="media in selectedMedia" :key="media.id">
                <img v-if="media.is_image" :src="media.small_thumb" alt="">
                <i v-else :class="`fa ${media.fa_icon}`" style="font-size: 60px;"></i>
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
            :before-close="handleClose"
            :title="trans('media.choose file')"
            width="75%"
        >
            <media-list :event-name="eventName" single-modal></media-list>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ trans('core.button.cancel') }}</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import axios from 'axios';
    import find from 'lodash/find';
    import forEach from 'lodash/forEach';
    import isEmpty from 'lodash/isEmpty';
    import reject from 'lodash/reject';
    import MediaList from './MediaList.vue';
    import RandomString from '../mixins/RandomString';
    import StringHelpers from '../../../../Core/Assets/js/mixins/StringHelpers';

    export default {
        components: { MediaList },
        mixins: [StringHelpers, RandomString],
        props: {
            zone: { required: true, type: String },
            entity: { required: true, type: String },
            entityId: { default: null, type: Number },
            label: { default: null, type: String },
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
                return this.selectedMedia !== undefined && !isEmpty(this.selectedMedia);
            },
        },
        watch: {
            entityId() {
                if (this.entityId) {
                    this.fetchMedia();
                }
            },
        },
        mounted() {
            if (this.entityId) {
                this.fetchMedia();
            }
            this.eventName = `file-was-selected${this.randomString()}${Math.floor(Math.random() * 999999)}`;

            this.$events.listen(this.eventName, (mediaData) => {
                if (find(this.selectedMedia, mediaData) === undefined) {
                    if (!this.selectedMedia) this.selectedMedia = [];
                    this.selectedMedia.push(mediaData);
                    this.$emit('multiple-file-selected', { ...mediaData, zone: this.zone });
                }
            });
        },
        methods: {
            handleClose(done) {
                done();
            },
            unSelectMedia(id) {
                this.selectedMedia = reject(this.selectedMedia, media => media.id === id);
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
                        forEach(this.selectedMedia, (file) => {
                            this.$emit('multiple-file-selected', { ...file, zone: this.zone });
                        });
                    });
            },
            getFieldLabel() {
                return this.label || this.ucwords(this.zone.replace('_', ' '));
            },
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
