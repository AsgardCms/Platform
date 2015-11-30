$(document).ready(function () {
    $("form").sisyphus({locationBased: true, excludeFields: $('input[name="_token"]')});
    $('[data-slug="source"]').each(function(){
	    $(this).slug();
	});
    $(document).ajaxStart(function() { Pace.restart(); });
});
