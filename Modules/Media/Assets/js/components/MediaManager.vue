<template>
    <div>
        <upload-zone></upload-zone>
        <media-list></media-list>
    </div>
</template>

<script>
    import UploadZone from './UploadZone.vue';
    import MediaList from './MediaList.vue';

    export default {
        components: {
            'upload-zone': UploadZone,
            'media-list': MediaList,
        },
        data() {
            return {}
        },
        methods: {},
        mounted() {
        }
    }
</script>
