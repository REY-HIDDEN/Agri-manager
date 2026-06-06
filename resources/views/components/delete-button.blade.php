@props(['route', 'confirm' => 'Are you sure?'])

<form action="{{ $route }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $confirm }}')">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger">
        <i class="fas fa-trash"></i>
    </button>
</form>
