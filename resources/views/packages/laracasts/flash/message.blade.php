@if (Session::has('flash_notification.message'))
    @if (Session::has('flash_notification.overlay'))
        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => 'Notice', 'body' => Session::get('flash_notification.message')])
    @else
        <?php $message = Session::get('flash_notification.message'); ?>
        <?php $level = Session::get('flash_notification.level'); ?>
        <?php if ($level == 'danger'): ?>
            <script type="text/javascript">
                $(document).ready( function() {
                    alertify.error( "<?php echo $message; ?>" );
                });
            </script>
        <?php endif; ?>
        <?php if ($level == 'success'): ?>
            <script type="text/javascript">
                $(document).ready( function() {
                    alertify.success( "<?php echo $message; ?>" );
                });
            </script>
        <?php endif; ?>
    @endif
@endif
