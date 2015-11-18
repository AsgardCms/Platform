$(document).ready(function () {
    $("form").sisyphus({locationBased: true, excludeFields: $('input[name="_token"]')});
    $('.slugify').slug();
});
