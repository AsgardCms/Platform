@if($group->shouldShowHeading())
    <li class="header">{{ $group->getName() }}</li>
@endif

@foreach($items as $item)
    {!! $item !!}
@endforeach
