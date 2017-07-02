<div class="form-group">
    {!! Form::label($zone, $name) !!}
    <div class="clearfix"></div>

    <a class="btn btn-primary btn-browse" onclick="openMediaWindowSingle(event, '{{ $zone }}');" <?php echo (isset($media->path))?'style="display:none;"':'' ?>><i class="fa fa-upload"></i>
        {{ trans('media::media.Browse') }}
    </a>

    <div class="clearfix"></div>

    <div class="jsThumbnailImageWrapper jsSingleThumbnailWrapper">
        <?php if (isset($media->path)): ?>
        <figure data-id="{{ $media->id }}">
            <?php if ($media->media_type === 'image'): ?>
            <img src="{{ Imagy::getThumbnail($media->path, (isset($thumbnailSize) ? $thumbnailSize : 'mediumThumb')) }}" alt="{{ $media->alt_attribute }}"/>
            <?php elseif ($media->media_type === 'video'): ?>
            <video src="{{ $media->path }}"  controls width="320"></video>
            <?php elseif ($media->media_type === 'audio'): ?>
            <audio controls><source src="{{ $media->path }}" type="{{ $media->mimetype }}"></audio>
            <?php else: ?>
            <i class="fa fa-file" style="font-size: 50px;"></i>
            <?php endif; ?>
            <a class="jsRemoveSimpleLink" href="#" data-id="{{ $media->pivot->id }}">
                <i class="fa fa-times-circle removeIcon"></i>
            </a>
        </figure>
        <input type="hidden" name="medias_single[{{ $zone }}]" value="{{ $media->id }}">
        <?php else: ?>
        <input type="hidden" name="medias_single[{{ $zone }}]" value="">
        <?php endif; ?>
    </div>
</div>
