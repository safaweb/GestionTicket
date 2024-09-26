<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Cordialement'),<br>
{{ config('app.name')}}.
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
<div class="row">   
    <div style="text-align: left;">
        @lang('SIMSOFT Technologies')<br>
        @lang('Tél: 50 52 08 05')<br>
        @lang('Email: commercial@simsoft.com.tn')<br>
        @lang('Adresse: 5 Avenue Léopold Senghor Espace Ayechi, 4000 Sousse – Tunisie')
    </div>
</div>
</x-slot:subcopy>
@endisset
</x-mail::message>
