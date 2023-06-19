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
          <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="570px">

              {{-- header message--}}
              <tr>
                  <td>
                      <table width="570px" style="overflow:hidden; background-size: 100% auto; background-color:#ffffff;background-image: url(https://msk-sait.ru/image/mail-fon-2.jpg); background-position: center; background-repeat: no-repeat; ">
                          <tr>
                              <td style="text-align:center;">

                              </td>
                          </tr>
                          <tr>
                              <td style="text-align:center;">
                                  <img style="vertical-align: bottom;" src="https://msk-sait.ru/image/Logo3.png" alt="logo">
                              </td>
                          </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td>
                      <table width="570px" style="background-color: #f5f5f5; padding: 15px; background-position: center; background-repeat: no-repeat;">
                          <tr>
                              <td>
                                  <div style="text-align:center; color:#373737;font-family:'yanone kaffeesatz' , 'open sans' , 'helvetica' , 'arial' , sans-serif;font-size:30px;line-height:1.2;text-align:center; font-weight: 600; ">Русский сервер для игры </div>
                                  <div style="text-align:center; color:#373737;font-family:'yanone kaffeesatz' , 'open sans' , 'helvetica' , 'arial' , sans-serif;font-size:20px;line-height:1.2;text-align:center; font-weight: 600;">Supreme Commander: Forged Alliance (RU SC)</div>
                              </td>
                          </tr>
                      </table>

                  </td>
              </tr>


              {{-- content message --}}
              <tr>
                  <td>
                      <table style="background-color: #fff; padding: 25px;" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
                         @yield('content')
                      </table>
                    </td>
                </tr>


              {{-- footer message --}}
              <tr>
                  <td>
                      <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
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

