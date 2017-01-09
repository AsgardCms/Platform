<div class="modal fade" id="keyboardShortcutsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    {{ trans('core::core.general.available keyboard shortcuts') }}
                </h4>
            </div>
            <div class="modal-body">
                @yield('shortcuts')
            </div>
        </div>
    </div>
</div>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> {{ $version }}
    </div>

    @yield('footer')
    <strong>Copyright © {{ date('Y')}} <a href="http://asgardcms.com" target="_blank">AsgardCms</a></strong>
</footer>

