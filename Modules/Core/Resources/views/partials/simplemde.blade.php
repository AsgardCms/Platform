@push('js-stack')
    <script>
        $( document ).ready(function() {
            $('.simplemde').each(function () {
                var simplemde = new SimpleMDE({
                    element: this,
                });
                simplemde.render();
            });
        });
    </script>
@endpush
