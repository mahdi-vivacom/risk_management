<div>
    <a href="{{ $btnUrl }}" {{ $attributes->merge(['class' => 'btn btn-'.$btnType]) }} data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
        <i {{ $attributes->merge(['class' => 'ri-'.$ricon]) }}></i>
        @isset($btnText)
            {{ $btnText }}
        @endisset
    </a>
</div>
