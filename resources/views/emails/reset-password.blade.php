
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
            <div style="margin-bottom:10px"> {{ __('Нажмите кнопку ниже, чтобы задать новый пароль.') }}</div>
        </td>
    </tr>

    <tr>
        <td>
            {{-- Action Button --}}
            <x-mail-button :link="$resetUrl">
                <x-slot name="text">
                    {{ __('Сбросить пароль') }}
                </x-slot>
            </x-mail-button>
        </td>
    </tr>


    <tr>
        <td>
            <div style="margin-bottom:15px; margin-top:30px"> {{ __('Если вы не отправляли запрос на смену пароля, то проигнорируйте данное письмо.') }}</div>
        </td>
    </tr>

    <tr>
         <td style="max-width: 520px;">
              {{ __('Если кнопка у вас не нажимается, скопируйте данную ссылку в адресную строку браузера и перейдите по ней:') }}
              <span class="break-all">{{ $resetUrl }}</span>
          </td>
    </tr>



@endsection
