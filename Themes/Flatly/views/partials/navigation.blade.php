<nav class="navbar navbar-default @guest navbar-fixed-top @endguest" style="@auth margin-top: -52px @endauth">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}">@setting('core::site-name')</a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
            @menu('main')
        </div>
    </div>
</nav>
