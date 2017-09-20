<template>
    <div>
        <label class="el-form-item__label">{{ getFieldLabel() }}</label>
        <div class="jsThumbnailImageWrapper jsSingleThumbnailWrapper" v-if="hasSelectedMedia">
            <figure>
                <img :src="this.selectedMedia.medium_thumb" alt="" v-if="this.selectedMedia.is_image"/>
                <i :class="`fa ${this.selectedMedia.fa_icon}`" style="font-size: 60px;" v-if="! this.selectedMedia.is_image"></i>
                <span v-if="! this.selectedMedia.is_image" style="display:block;">{{ this.selectedMedia.filename }}</span>
            </figure>
            <div class="clearfix"></div>
            <el-button type="button" @click="unSelectMedia">{{ trans('media.remove media') }}</el-button>
        </div>
        <div class="" v-else>
            <el-button type="button" @click="dialogVisible = true">{{ trans('media.Browse') }}</el-button>
        </div>

        <el-dialog
                :title="trans('media.upload file')"
                :visible.sync="dialogVisible"
                size="full"
                :before-close="handleClose">

            <upload-zone></upload-zone>
            <media-list single-modal></media-list>

            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ trans('core.button.cancel') }}</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import UploadZone from './UploadZone.vue';
    import MediaList from './MediaList.vue';
    import StringHelpers from '../../../../Core/Assets/js/mixins/StringHelpers'
    import axios from 'axios'

    export default {
        mixins: [StringHelpers],
        props: {
            zone: {type: String, required: true},
            entity: {type: String, required: true},
            entityId: {type: Number},
            label: {type: String},
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
                selectedMedia: {},
            }
        },
        computed: {
            hasSelectedMedia() {
                return ! _.isEmpty(this.selectedMedia);
            },
        },
        methods: {
            handleClose(done) {
                done();
            },
            unSelectMedia() {
                this.selectedMedia = {}
            },
            fetchMedia() {
                axios.get(route('api.media.find-first-by-zone-and-entity', {
                    zone: this.zone,
                    entity: this.entity,
                    entity_id: this.entityId,
                }))
                    .then(response => {
                        this.selectedMedia = response.data.data;
                    })
            },
            getFieldLabel() {
                return this.label || this.ucwords(this.zone.replace('_', ' '));
            },
        },
        mounted() {
            this.$events.listen('fileWasSelected', mediaData => {
                this.dialogVisible = false;
                this.selectedMedia = mediaData;
                this.$emit('singleFileSelected', _.merge(mediaData, {zone: this.zone}));
            });
        }
    }
</script>
