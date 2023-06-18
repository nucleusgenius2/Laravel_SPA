
@extends('layout.mail')
@section('content')
<tr>
    <td>
        <div style="padding:20px; text-align: center">
        {{-- Greeting --}}
        @if (! empty($greeting))
        {{ $greeting }}
        @else
        @if ($level === 'error')
        @lang('Ошибка')
        @else
        @lang('Привествуем!')
        @endif
        @endif
        </div>
    </td>
</tr>

<tr>
    <td>
    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
    {{ $line }}

    @endforeach
    </td>
</tr>

<tr>
    <td>
    {{-- Action Button --}}
    @isset($actionText)
        <?php
            $color = match ($level) {
                'success', 'error' => $level,
                default => 'primary',
            };
        ?>
        <x-mail-button :link="$actionUrl">
        {{ $actionText }}
        </x-mail-button>

    @endisset
    </td>
</tr>


<tr>
    <td>
    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
    {{ $line }}

    @endforeach
    </td>
</tr>



{{-- Subcopy --}}
@isset($actionText)

<tr>
    <td>

    "Если кнопка у вас не нажимается, скопируйте данную ссылку в адресную строку браузера и перейдите по ней:"
    <span class="break-all">({{ $actionUrl }})</span>
    </td>
</tr>

@endisset

@endsection
