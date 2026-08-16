@props(['type' => 'success', 'message' => ''])

<div class="alert alert-{{ $type }} alert-dismissible fade show mb-3 mx-2" role="alert">
    <button type="button" class="btn-close close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
    @if ($type === 'success')
        <i class="fa fa-check-circle me-1"></i>
    @elseif ($type === 'danger')
        <i class="fa fa-exclamation-circle me-1"></i>
    @endif
    {{ $message }}
</div>
