@if($group->shouldShowHeading())
    <li class="header">{{ $group->name }}</li>
@endif

@foreach($group->getItems() as $item)
    {!! $item->render() !!}
@endforeach
