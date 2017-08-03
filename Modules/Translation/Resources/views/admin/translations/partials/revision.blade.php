<tr>
    <td>
        @if ($history->oldValue() !== null)
            {{ $history->oldValue() }}
        @endif
    </td>
    <td>{{ $history->userResponsible()->present()->fullname() }}</td>
    <td>
        @if ($history->oldValue() === null)
            {{ trans('translation::translations.created') }}
        @else
            {{ trans('translation::translations.edited') }}
        @endif
    </td>
    <td>
        <span data-toggle="tooltip" title="{{ $history->created_at }}">
            {{ $history->created_at->diffForHumans() }}
        </span>
    </td>
    <td>
        @if ($history->oldValue() !== null)
            <span data-toggle="tooltip" title="{{ trans('translation::translations.revert history') }}">
                <a href="{{ route('admin.translation.translation.update', [$history->revisionable_id, 'oldValue' => $history->oldValue()]) }}">
                    <i class="fa fa-history"></i>
                </a>
            </span>
        @endif
    </td>
</tr>
