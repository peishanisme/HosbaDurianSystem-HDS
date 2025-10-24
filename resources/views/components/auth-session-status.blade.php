@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success text-center mb-10']) }}>
        {{ $status }}
    </div>
@endif
