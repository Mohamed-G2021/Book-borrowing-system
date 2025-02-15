@props([
    'title' => null,
    'header' => null,
    'footer' => null,
    'class' => '',
    'headerClass' => 'bg-primary text-white',
    'bodyClass' => '',
    'footerClass' => ''
])

<div {{ $attributes->merge(['class' => "card {$class}"]) }}>
    @if($title || $header)
        <div class="card-header {{ $headerClass }}">
            @if($title)
                <h5 class="card-title mb-0">{{ $title }}</h5>
            @endif
            
            @if($header)
                {{ $header }}
            @endif
        </div>
    @endif

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer {{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif
</div>