<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>

    <![endif]-->
</head>
<body>

<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="email-masthead">
                        <a href="" class="f-fallback email-masthead_name">
                            Order Manager CCP
                        </a>
                    </td>
                </tr>
                <!-- Email Body -->
                <tr>
                    <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    <div class="f-fallback">
                                        <h1>Hola {{ $user->name }},</h1>
                                        <p class="text-justify">
                                            Recientemente solicitó restablecer su contraseña para su cuenta de Order Manager. Use el botón de abajo para restablecerla. <strong>Este restablecimiento de contraseña solo es válido durante las próximas 24 horas.</strong>
                                        </p>
                                        <!-- Action -->
                                        <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td align="center">

                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
                                                        <tr>
                                                            <td align="center">
                                                                <a href="http://ordermanager.test/api/validtoken/{{ $token }}" class="f-fallback button button--green" target="_blank">Resetea tu contraseña</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <p text-justify>
                                            Por seguridad, si no solicitó un restablecimiento de contraseña, ignore este correo electrónico.</p>
                                        <p>Gracias,
                                            <br>El equipo de Order Manager CCP</p>
                                        <!-- Sub copy -->
                                        <table class="body-sub" role="presentation">
                                            <tr>
                                                <td>
                                                    <p class="f-fallback sub text-justify" >
                                                        Si tiene problemas con el botón de arriba, copie y pegue la URL a continuación en su navegador web.
                                                    </p>
                                                    <p class="f-fallback sub">http://ordermanager.test/api/validtoken/{{ $token }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td class="content-cell" align="center">
                                    <p class="f-fallback sub align-center">&copy; 2019 Order Manager CCP. Todos los derechos reservados.</p>
                                    <p class="f-fallback sub align-center">
                                        Cuidado con el Perro
                                    </p>
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
</html>
