@extends('layouts.master')

@section('content-header')
@stop

@push('css-stack')
@endpush

@section('content')
@stop

@push('js-stack')
<?php $config = config('asgard.media.config'); ?>
<script>
    var maxFilesize = '<?php echo $config['max-file-size'] ?>',
            acceptedFiles = '<?php echo $config['allowed-types'] ?>';
</script>

@endpush
