@props(['link' => ''])

<div style="text-align: center">

    <div
        style="display:inline-block;
        background-color: #5a9cff;
        border-color: #5a9cff;
        border-radius: 4px;
        border-style: solid;
        padding: 8px 18px 8px 18px;
        box-sizing: border-box;
        color: #fff;
        font-family: '-apple-system' , 'blinkmacsystemfont' , 'segoe ui' , 'roboto' , 'helvetica' , 'arial' , sans-serif , 'apple color emoji' , 'segoe ui emoji' , 'segoe ui symbol';
        font-size: 15px;
        overflow: hidden;
        text-decoration: none;"
    >
        <a style="color:#fff; text-decoration: none;" href="{{ $link }}" target="_blank">
            {{ $text }}
        </a>
    </div>
</div>
