<template>
    <div class="wrap-footer">
        <div class="footer max">
            <div class="footer-col-1">СООБЩЕСТВО</div>

            <div class="footer-col-2">
                <p><a class="soc" href="https://vk.com/ru_faf" target="_blank" rel="noopener"><br />
                    <img src="/images/vk_1.png" alt="vk" /></a></p>
                <p><a class="soc" href="https://www.youtube.com/channel/UCBMPgH4x4oBYOBghyTM1tgA" target="_blank" rel="noopener"><br />
                    <img src="/images/youtube_1.png" alt="youtube" /></a></p>
                <p><a class="soc" href="https://discord.gg/rDhgNbUPqf" target="_blank" rel="noopener"><br />
                    <img src="/images/discord.png" alt="discord" /></a></p>
                <div class="text-soc">Основное общение в дискорде</div>
            </div>

            <div class="footer-col-3">
                <div class="wrap-button">
                    <div class="button-01 hover-purple">Скачать клиент</div>
                </div>
            </div>

        </div>
    </div>
</template>



<style scoped>
    .footer {
        display:flex;
        align-items: center;
        padding-top: 45px;
        padding-bottom: 45px;
    }

    .footer-col-1 {
        color:#fff;
        margin: 0;
        font-weight: 900;
        font-size: 36px;
        text-transform: uppercase;
        flex-basis:30%;
    }

    .footer-col-2 {
        display:flex;
        align-items: center;
        flex-basis:40%;
        border: 1px solid #333333;
        border-top: 0px;
        border-bottom: 0px;
        padding: 20px;
    }

    .footer-col-2 p {
        margin-top: 0px;
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 0px;
        line-height: 0px;

    }
    .footer-col-3 {
        flex-basis:30%;
        justify-content: flex-end;
        display: flex;
    }

    .wrap-footer {
        background-color: #151515;
        margin-top: 30px;
    }

    .text-soc {
        font-size: 16px;
        color: #4E41D9;
        margin-bottom: 0px;
        margin-left: 20px;
    }

    .button-01 {
        position: relative;
        overflow: hidden;
        display: inline-block;
        font-weight: bold;
        font-size: 14px;
        line-height: 19px;
        color: #4E41D9;
        border: 1px solid #4E41D9;
        box-sizing: border-box;
        border-radius: 4px;
        padding: 10px 25px;
        transition: 0.3s;
        cursor: pointer;
    }

    .button-01:before {
        content: "";
        height: 70px;
        width: 20px;
        background: #4E41D9;
        position: absolute;
        top: -21px;
        transform: rotate(24deg);
        left: -20%;
        transition: 0.4s;
    }
    .button-01:hover:before {
        left: 120%;
    }
    .button-01.hover-purple {
        background: #4E41D9;
        color: #fff;
    }

    .button-01.hover-purple:before { background: #fff; }
    .button-01:hover {color: #4E41D9;}
    .button-01.hover-purple:hover {color: #fff;}

</style>
