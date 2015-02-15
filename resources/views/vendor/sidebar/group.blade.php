@if($group->enabled)
    <p class="menu-title">{{ $group->name }} <span class="pull-right"></span></p>
@endif

<ul class="sidebar-menu @if(!$group->enabled) no-groups @endif">
    @foreach($group->getItems() as $item)
        {!! $item->render() !!}
    @endforeach
</ul>
