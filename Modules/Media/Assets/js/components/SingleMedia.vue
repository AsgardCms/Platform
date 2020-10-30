<template>
    <div>
        <label class="el-form-item__label">{{ getFieldLabel() }}</label>
        <div v-if="hasSelectedMedia" class="jsThumbnailImageWrapper jsSingleThumbnailWrapper">
            <figure>
                <img v-if="selectedMedia.is_image" :src="selectedMedia.medium_thumb" alt="">
                <i v-else :class="`fa ${selectedMedia.fa_icon}`" style="font-size: 60px;"></i>
                <span v-if="!selectedMedia.is_image" style="display:block;">{{ selectedMedia.filename }}</span>
            </figure>
            <div class="clearfix"></div>
            <el-button type="button" @click="unSelectMedia">{{ trans('media.remove media') }}</el-button>
        </div>
        <div v-else class="">
            <el-button type="button" @click="dialogVisible = true">{{ trans('media.Browse') }}</el-button>
        </div>

        <el-dialog :visible.sync="dialogVisible" :before-close="handleClose" :title="trans('media.choose file')" width="75%">
            <media-list :event-name="eventName" single-modal></media-list>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ trans('core.button.cancel') }}</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import axios from 'axios';
    import isEmpty from 'lodash/isEmpty';
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
                selectedMedia: {},
                eventName: '',
            };
        },
        computed: {
            hasSelectedMedia() {
                return !isEmpty(this.selectedMedia);
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
                this.dialogVisible = false;
                this.selectedMedia = mediaData;
                this.$emit('single-file-selected', { ...mediaData, zone: this.zone });
            });
        },
        methods: {
            handleClose(done) {
                done();
            },
            unSelectMedia() {
                this.selectedMedia = {};
                this.$emit('single-file-selected', { id: null, zone: this.zone });
            },
            fetchMedia() {
                axios.get(route('api.media.find-first-by-zone-and-entity', {
                        zone: this.zone,
                        entity: this.entity,
                        entity_id: this.entityId,
                    }))
                    .then((response) => {
                        this.$emit('single-file-selected', { ...response.data.data, zone: this.zone });
                        this.selectedMedia = response.data.data;
                    });
            },
            getFieldLabel() {
                return this.label || this.ucwords(this.zone.replace('_', ' '));
            },
        },
    };
</script>
