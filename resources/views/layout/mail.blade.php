<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Email</title>
    <style type="text/css">
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
        }

        img {
            max-width: 100%;
        }

    </style>
</head>

<body style="margin: 0;padding: 0;">
<table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; background-color: #fafafa;" width="100%">
    <tr>
        <td>
          <table data-2='213' align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="570px"
                       style="background-color:#ffffff;border-color:#e8e5ef;border-radius:2px;border-width:1px;box-sizing:border-box;font-family:'-apple-system' , 'blinkmacsystemfont' , 'segoe ui' , 'roboto' , 'helvetica' , 'arial' , sans-serif , 'apple color emoji' , 'segoe ui emoji' , 'segoe ui symbol';margin:0 auto 0 auto;padding:0;max-width:570px"
          >
              {{-- header message--}}
              <tr>
                  <td>

                      <table style="background-image:url('https://msk-sait.ru/image/mail-fon-2.jpg'); background-size: 100% auto; padding:25px" width="100%">
                          <tr>
                              <td style="text-align:center; padding-top:30px; padding-bottom:20px">
                                  <img src="https://msk-sait.ru/image/logoRUSC.png" alt="logo">
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <div style="text-align:center; font-size:35px;line-height:1; color: #fff; padding-bottom:30px">RU SC</div>
                                  <div style="text-align:center; color:white;font-family:'yanone kaffeesatz' , 'open sans' , 'helvetica' , 'arial' , sans-serif;font-size:30px;line-height:1.2;text-align:center; font-weight: 600;">Русский сервер для игры </div>
                                  <div style="text-align:center; color:white;font-family:'yanone kaffeesatz' , 'open sans' , 'helvetica' , 'arial' , sans-serif;font-size:30px;line-height:1.2;text-align:center; font-weight: 600; padding-bottom:40px">Supreme Commander: Forged Alliance </div>
                              </td>
                          </tr>
                      </table>

                  </td>
              </tr>


              {{-- content message --}}
              <tr>
                  <td>
                      <table style="background-color: #fff;" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
                         @yield('content')
                      </table>
                    </td>
                </tr>


              {{-- footer message --}}
              <tr>
                  <td>
                      <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-top:20px;" width="100%">
                          <tr style="background-color: #2d2d2d;">
                              <td style="color: #fff;">
                                  <div style="padding:20px;">
                                    <div style="padding-bottom:20px;">По всем вопросам обращайтесь в наш <a style="color: #5a9cff; text-decoration: none" href="">Дискорд</a></div>
                                    <div>Официальный сайт <a style="color: #5a9cff; text-decoration: none" href="">Сайт</a></div>
                                  </div>
                              </td>
                          </tr>
                      </table>
                </td>
              </tr>


          </table>
        </td>
    </tr>
</table>
</body>

