$(document).ready(function () {
    $("form").sisyphus({locationBased: true, excludeFields: $('input[name="_token"]')});

    $('[data-slug="source"]').each(function(){
	    $(this).slug();
	});

    $(document).ajaxStart(function() { Pace.restart(); });

    Mousetrap.bind('f1', function() {
        window.open('https://asgardcms.com/docs', '_blank');
    });
});
