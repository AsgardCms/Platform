<script>
    if (typeof window.openMediaWindowSingleOld === 'undefined') {
        window.mediaZone = '';
        window.openMediaWindowSingleOld = function (event, zone) {
            window.mediaZone = zone;
            window.single = true;
            window.old = true;
            window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
            window.open(Asgard.mediaGridSelectUrl, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
        };
    }
    if (typeof window.includeMediaSingleOld === 'undefined') {
        window.includeMediaSingleOld = function (mediaId) {
            $.ajax({
                type: 'POST',
                url: Asgard.mediaLinkUrl,
                data: {
                    'mediaId': mediaId,
                    '_token': '{{ csrf_token() }}',
                    'entityClass': '{{ $entityClass }}',
                    'entityId': '{{ $entityId }}',
                    'zone': window.mediaZone
                },
                success: function (data) {
                    var mediaPlaceholder;

                    if (data.result.mediaType === 'image') {
                        mediaPlaceholder = '<img src="' + data.result.path + '" alt=""/>';
                    } else if (data.result.mediaType == 'video') {
                        mediaPlaceholder = '<video src="' + data.result.path + '" controls width="320"></video>';
                    } else if (data.result.mediaType == 'audio') {
                        mediaPlaceholder = '<audio controls><source src="' + data.result.path + '" type="' + data.result.mimetype + '"></audio>'
                    } else {
                        mediaPlaceholder = '<i class="fa fa-file" style="font-size: 50px;"></i>';
                    }

                    var html = '<figure data-id="' + data.result.imageableId + '">' + mediaPlaceholder +
                            '<a class="jsRemoveSimpleLink" href="#" data-id="' + data.result.imageableId + '">' +
                            '<i class="fa fa-times-circle removeIcon"></i>' +
                            '</a></figure>';
                    window.zoneWrapper.append(html).fadeIn('slow', function() {
                        toggleButton($(this));
                    });
                }
            });
        };
    }
</script>
<div class="form-group">
    {!! Form::label($zone, ucwords(str_replace('_', ' ', $zone)) . ':') !!}
    <div class="clearfix"></div>

    <a class="btn btn-primary btn-browse" onclick="openMediaWindowSingleOld(event, '{{ $zone }}');" <?php echo (isset(${$zone}->path))?'style="display:none;"':'' ?>><i class="fa fa-upload"></i>
        {{ trans('media::media.Browse') }}
    </a>

    <div class="clearfix"></div>

    <div class="jsThumbnailImageWrapper jsSingleThumbnailWrapper">
        <?php if (isset(${$zone}->path)): ?>
            <figure data-id="{{ ${$zone}->pivot->id }}">
            <?php if (${$zone}->media_type == 'image'): ?>
                <img src="{{ Imagy::getThumbnail(${$zone}->path, (isset($thumbnailSize) ? $thumbnailSize : 'mediumThumb')) }}" alt="{{ ${$zone}->alt_attribute }}"/>
            <?php elseif (${$zone}->media_type == 'video'): ?>
                <video src="{{ ${$zone}->path }}"  controls width="320"></video>
            <?php elseif (${$zone}->media_type == 'audio'): ?>
                <audio controls><source src="{{ ${$zone}->path }}" type="{{ ${$zone}->mimetype }}"></audio>
            <?php else: ?>
                <i class="fa fa-file" style="font-size: 50px;"></i>
            <?php endif; ?>
                <a class="jsRemoveSimpleLink" href="#" data-id="{{ ${$zone}->pivot->id }}">
                    <i class="fa fa-times-circle removeIcon"></i>
                </a>
            </figure>
        <?php endif; ?>
    </div>
</div>
<script>
    $( document ).ready(function() {
        $('.jsThumbnailImageWrapper').off('click', '.jsRemoveSimpleLink');
        $('.jsThumbnailImageWrapper').on('click', '.jsRemoveSimpleLink', function (e) {
            e.preventDefault();
            var imageableId = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: Asgard.mediaUnlinkUrl,
                data: {
                    'imageableId': imageableId,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.error === false) {
                        $(e.delegateTarget).fadeOut('slow', function() {
                            toggleButton($(this));
                        }).html('');
                    } else {
                        $(e.delegateTarget).append(data.message);
                    }
                }
            });
        });
    });

    function toggleButton(el) {
        var browseButton = el.parent().find('.btn-browse');
        browseButton.toggle();
    }
</script>
