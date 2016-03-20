<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="margin: 0; padding: 0;">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body bgcolor="#f6f6f6" style="-webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;">
        <table class="body-wrap" bgcolor="#f6f6f6" style="width: 100%; margin: 0; padding: 20px;">
            <tr>
                <td></td>
                <td bgcolor="#FFFFFF" style="display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0;">
                    <div style="max-width: 600px; display: block; margin: 0 auto; padding: 0;">
                        @yield("content")
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
        <table class="footer-wrap" bgcolor="#f6f6f6" style="width: 100%; clear: both !important; margin: 0; padding: 0;">
            <tr> 
                <td></td>
                <td style="display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">
                    <div style="max-width: 600px; display: block; margin: 0 auto; padding: 0;">
                        <table style="width: 100%; margin: 0; padding: 0;">
                            <tr>
                                <td align="center">
                                    <p style="font-size: 12px; line-height: 1.6; color: #666; margin: 0 0 10px; padding: 0;">
                                        © 2016
                                        <a href="{{config('app.url')}}" style="font-size: 100%; line-height: 1.6; color: #999; margin: 0; padding: 0;">
                                            {{sitename()}}
                                        </a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
    </body>
</html>