<span class="label label-{{ $label }}">{{ $title }}</span>
<ul>
    <?php foreach ($data as $dataLine): ?>
    <li class="text-{{ $color }}">
        {!! $dataLine !!}
    </li>
    <?php endforeach; ?>
</ul>
