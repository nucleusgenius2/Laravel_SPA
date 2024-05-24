
@extends('layout.mail')
@section('content')
    <tr>
        <td>
            <div style="padding:20px; text-align: center; font-size: 20px; color: #3c3c3c; font-weight: 600; margin-bottom:10px">
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
            <div style="margin-bottom:10px"> {{ __('Нажмите кнопку ниже, чтобы подтвердить свой адрес электронной почты.') }}</div>
        </td>
    </tr>

    <tr>
        <td>
            {{-- Action Button --}}
            @isset($actionText)

                <x-mail-button :link="$verificationUrl">
                    <x-slot name="text">
                        {{ __('Подтвердить email') }}
                    </x-slot>
                </x-mail-button>

            @endisset
        </td>
    </tr>


    <tr>
        <td>
            <div style="margin-bottom:15px; margin-top:30px"> {{ __('Если вы не регистрировались на сайте САЙТ, то проигнорируйте данное письмо.') }}</div>
        </td>
    </tr>

    <tr>
        <td style="max-width: 520px;">
            {{ __('Если кнопка у вас не нажимается, скопируйте данную ссылку в адресную строку браузера и перейдите по ней:') }}
            <span class="break-all">{{ $verificationUrl }}</span>
        </td>
    </tr>


@endsection
