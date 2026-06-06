@props(['status', 'type' => 'delivery'])

@php
    if ($type === 'payment') {
        $class = match($status) {
            'paid' => 'bg-success',
            'partial' => 'badge-partial',
            default => 'badge-pending',
        };
        $label = ucfirst($status);
    } else {
        $class = match($status) {
            'delivered' => 'bg-success',
            'in_transit' => 'badge-transit',
            default => 'badge-pending',
        };
        $label = str_replace('_', ' ', ucfirst($status));
    }
@endphp

<span class="badge {{ $class }} text-dark">{{ $label }}</span>
