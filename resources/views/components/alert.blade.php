@props([
    'type' => 'success',
    'message' => session('success') ?: session('error'),
    'timeout' => 2000
])

@if($message)
<div 
    {{ $attributes->merge([
        'class' => 'alert alert-' . ($message === session('success') ? 'success' : 'danger') . 
                   ' alert-dismissible fade show'
    ]) }} 
    role="alert"
    data-bs-dismiss="alert"
    data-timeout="{{ $timeout }}"
>
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[data-timeout]');
    alerts.forEach(alert => {
        const timeout = alert.dataset.timeout || 1000;
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, timeout);
    });
});
</script>
@endpush