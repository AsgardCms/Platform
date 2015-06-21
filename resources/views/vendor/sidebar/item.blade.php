<li class="@if($active)active @endif @if($item->hasItems()) treeview @endif clearfix">
    <a href="{{ $item->getUrl() }}" @if(count($appends) > 0)class="hasAppend"@endif>
        <i class="{{ $item->getIcon() }}"></i>
        <span>{{ $item->getName() }}</span>

        @foreach($badges as $badge)
            {!! $badge !!}
        @endforeach

        @if($item->hasItems())<i class="{{ $item->getToggleIcon() }} pull-right"></i>@endif
    </a>

    @foreach($appends as $append)
        {!! $append !!}
    @endforeach

    @if(count($items) > 0)
        <ul class="treeview-menu">
            @foreach($items as $item)
                {!! $item !!}
            @endforeach
        </ul>
    @endif
</li>
