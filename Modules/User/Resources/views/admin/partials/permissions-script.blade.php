<script>
    $( document ).ready(function() {
        $('.jsSelectAllAllow').on('click',function (event) {
            event.preventDefault();
            $(this).closest('.permissionGroup').find('.jsAllow').each(function (index, value) {
                $(value).iCheck('check');
            });
        });
        $('.jsSelectAllDeny').on('click',function (event) {
            event.preventDefault();
            $(this).closest('.permissionGroup').find('.jsDeny').each(function (index, value) {
                $(value).iCheck('check');
            });
        });
        $('.jsSelectAllInherit').on('click',function (event) {
            event.preventDefault();
            $(this).closest('.permissionGroup').find('.jsInherit').each(function (index, value) {
                $(value).iCheck('check');
            });
        });
    });
</script>
