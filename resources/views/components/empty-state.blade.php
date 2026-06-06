@props(['icon', 'title', 'message', 'actionRoute' => null, 'actionLabel' => null, 'colspan' => 7])

<tr>
    <td colspan="{{ $colspan }}">
        <div class="empty-state">
            <i class="fas fa-{{ $icon }}"></i>
            <h5>{{ $title }}</h5>
            <p>{{ $message }}</p>
            @if($actionRoute && $actionLabel)
                <a href="{{ $actionRoute }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> {{ $actionLabel }}
                </a>
            @endif
        </div>
    </td>
</tr>
