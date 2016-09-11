<script>
    var Asgard = {
        backendUrl: '{{ config('asgard.core.core.admin-prefix') }}',
        mediaGridCkEditor : '{{ route('media.grid.ckeditor') }}',
        mediaGridSelectUrl: '{{ route('media.grid.select') }}',
        dropzonePostUrl: '{{ route('api.media.store') }}',
        mediaSortUrl: '{{ route('api.media.sort') }}',
        mediaLinkUrl: '{{ route('api.media.link') }}',
        mediaUnlinkUrl: '{{ route('api.media.unlink') }}'
    };
</script>
