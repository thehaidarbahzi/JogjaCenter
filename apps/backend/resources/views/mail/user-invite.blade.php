<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan JogjaCenter</title>
</head>
<body style="margin: 0; padding: 32px 0; background: #f5f5f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; color: #24292f; -webkit-font-smoothing: antialiased;">

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 16px 0;">
                <a href="#" style="text-decoration: none; display: inline-block;">
                    <img src="{{ $message->embed(public_path('icon.png')) }}" alt="JogjaCenter" width="48" height="48" style="display: block; width: 48px; height: auto;">
                </a>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="560" style="max-width: 580px; width: 100%; background: #ffffff; border-radius: 12px; border: 1px solid #e0e0e0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="padding: 1.5em;">
                            <h1 style="font-size: 1.5rem; font-weight: 700; color: #1a1a2e; text-align: center; margin: 0 0 24px 0; line-height: calc(2 / 1.5);">Silakan verifikasi identitas Anda</h1>

                            <p style="font-size: 1rem; color: #333; line-height: calc(1.5 / 1); margin: 0 0 16px 0;">Berikut adalah invitasi untuk membuat akun JogjaCenter</p>

                            <div style="margin: 24px 0; text-align: center;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                                    <tr>
                                        <td style="border-radius: 0.25em; background: #007bff; text-align: center;">
                                            <a href="{{ url('/activate/' . $link) }}" style="display: inline-block; padding: 0.85em 1.5em; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 1rem; font-family: inherit; border-radius: 6px;">Buat Akun</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style="font-size: 0.875rem; color: #333; line-height: calc(1.25 / 0.875);">Invitasi ini berlaku selama <strong>1 hari</strong> dan hanya bisa digunakan satu kali.
                            <strong>Mohon jangan bagikan invitasi ini kepada pihak mana pun.</strong> Jika Anda tidak mengajukan permintaan ini, silakan abaikan pesan ini.
                            </p>

                            <p style="font-size: 0.875rem; color: #333; line-height: calc(1.25 / 0.875);">
                                Hormat kami,<br>
                                <strong>JogjaCenter</strong>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 1em 0;">
                <p style="font-size: 0.75rem; color: #666; line-height: calc(1 / 0.75); max-width: 580px;">
                    Anda menerima email ini karena anda diundang untuk membuat akun JogjaCenter.
                    <br/>Jika ini bukan Anda, silakan abaikan pesan ini.
                </p>
                <p style="font-size: 0.75rem; color: #999; line-height: calc(1 / 0.75); margin: 12px 0 0 0;">
                    JogjaCenter &middot; <a href="#" style="color: #666; text-decoration: underline;">Kebijakan Privasi</a>
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
