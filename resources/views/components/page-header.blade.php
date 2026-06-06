@props(['icon', 'title', 'actionRoute' => null, 'actionLabel' => null, 'actionStyle' => 'btn-primary', 'backRoute' => null])

<div class="page-header">
    <h2><i class="fas fa-{{ $icon }} me-2"></i>{{ $title }}</h2>
    @if($backRoute)
        <a href="{{ $backRoute }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    @elseif($actionRoute && $actionLabel)
        <a href="{{ $actionRoute }}" class="btn {{ $actionStyle }}">
            <i class="fas fa-plus me-1"></i> {{ $actionLabel }}
        </a>
    @endif
</div>
