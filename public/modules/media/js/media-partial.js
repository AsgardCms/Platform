$( document ).ready(function() {
	var $fileCount = $('.jsFileCount');
	var sortableWrapper = $(".jsThumbnailImageWrapper");
	var sortableSelection = sortableWrapper.not(".jsSingleThumbnailWrapper");

	// This comes from new-file-link-single
	if (typeof window.openMediaWindowSingle === 'undefined') {
		window.mediaZone = '';
		window.openMediaWindowSingle = function (event, zone) {
			window.single = true;
			window.old = false;
			window.mediaZone = zone;
			window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
			window.open(Asgard.mediaGridSelectUrl, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
		};
	}
	if (typeof window.includeMediaSingle === 'undefined') {
		window.includeMediaSingle = function (mediaId, filePath, mediaType, mimetype) {
			var mediaPlaceholder;

			if (mediaType === 'image') {
				mediaPlaceholder = '<img src="' + filePath + '" alt=""/>';
			} else if (mediaType == 'video') {
				mediaPlaceholder = '<video src="' + filePath + '" controls width="320"></video>';
			} else if (mediaType == 'audio') {
				mediaPlaceholder = '<audio controls><source src="' + filePath + '" type="' + mimetype + '"></audio>'
			} else {
				mediaPlaceholder = '<i class="fa fa-file" style="font-size: 50px;"></i>';
			}

			var html = '<figure data-id="'+ mediaId +'">' + mediaPlaceholder +
				'<a class="jsRemoveSimpleLink" href="#" data-id="' + mediaId + '">' +
				'<i class="fa fa-times-circle removeIcon"></i></a>' +
				'</figure>';
			window.zoneWrapper.append(html).fadeIn('slow', function() {
				toggleButton($(this));
			});
                        window.zoneWrapper.children('input').val(mediaId);
		};
	}

	// This comes from new-file-link-multiple
	if (typeof window.openMediaWindowMultiple === 'undefined') {
		window.mediaZone = '';
		window.openMediaWindowMultiple = function (event, zone) {
			window.single = false;
			window.old = false;
			window.mediaZone = zone;
			window.zoneWrapper = $(event.currentTarget).siblings('.jsThumbnailImageWrapper');
			window.open(Asgard.mediaGridSelectUrl, '_blank', 'menubar=no,status=no,toolbar=no,scrollbars=yes,height=500,width=1000');
		};
	}
	if (typeof window.includeMediaMultiple === 'undefined') {
		window.includeMediaMultiple = function (mediaId, filePath, mediaType, mimetype) {
			var mediaPlaceholder;

			if (mediaType === 'image') {
				mediaPlaceholder = '<img src="' + filePath + '" alt=""/>';
			} else if (mediaType == 'video') {
				mediaPlaceholder = '<video src="' + filePath + '" controls width="320"></video>';
			} else if (mediaType == 'audio') {
				mediaPlaceholder = '<audio controls><source src="' + filePath + '" type="' + mimetype + '"></audio>'
			} else {
				mediaPlaceholder = '<i class="fa fa-file" style="font-size: 50px;"></i>';
			}

			var html = '<figure data-id="' + mediaId + '">' + mediaPlaceholder +
				'<a class="jsRemoveLink" href="#" data-id="' + mediaId + '">' +
				'<i class="fa fa-times-circle removeIcon"></i>' +
				'</a>' +
				'<input type="hidden" name="medias_multi[' + window.mediaZone + '][files][]" value="' + mediaId + '">' +
				'</figure>';
			window.zoneWrapper.append(html).fadeIn();
			window.zoneWrapper.trigger('sortupdate');
			if ($fileCount.length > 0) {
				var count = parseInt($fileCount.text());
				$fileCount.text(count + 1);
			}
		};
	}

	// This comes from new-file-link-multiple
	sortableWrapper.on('click', '.jsRemoveLink', function (e) {
		e.preventDefault();
		var pictureWrapper = $(this).parent();
		var pictureSortable = pictureWrapper.parent();

		pictureWrapper.fadeOut().remove();
		pictureSortable.trigger('sortupdate');

		if ($fileCount.length > 0) {
			var count = parseInt($fileCount.text());
			$fileCount.text(count - 1);
		}
	});

	// This comes from new-file-link-multiple
	sortableSelection.sortable({
		items: "figure",
		placeholder: 'ui-state-highlight',
		cursor:'move',
		helper: 'clone',
		containment: 'parent',
		forcePlaceholderSize: false,
		forceHelperSize: true
	});

	sortableSelection.on('sortupdate', function(e, ui) {
		var dataSortable = $(this).sortable('toArray', {attribute: 'data-id'});
		$(this).find($('.orders')).val(dataSortable);
	});

	// This comes from new-file-link-single
	sortableWrapper.off('click', '.jsRemoveSimpleLink');
	sortableWrapper.on('click', '.jsRemoveSimpleLink', function (e) {
		e.preventDefault();
		$(e.delegateTarget).fadeOut('slow', function() {
			toggleButton($(this));
		}).children('figure').remove();
		$(e.delegateTarget).children('input').val('');
	});

	function toggleButton(el) {
		var browseButton = el.parent().find('.btn-browse');
		browseButton.toggle();
	}
});
