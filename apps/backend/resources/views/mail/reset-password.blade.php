<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        .logo {
            width: 5rem;
            height: 5rem;
        }

        .logo svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        .title {
            font-size: 1.5rem;
        }

        .card {
            max-width: 42rem;
            margin: 0 auto;
            padding: 1rem;
            border: 1px solid #808080;
            border-radius: 12px;
            text-align: left;
        }

        .code {
            text-align: center;
            letter-spacing: 8px;
        }
    </style>
</head>
<body style="margin: 0; padding: 24px; font-family: Arial, sans-serif; color: #222222;">
    <div style="margin: 0 0 24px; text-align: center;">
        <img src="{{ $message->embed(public_path('icon.png')) }}" width="80" height="104" alt="JogjaCenter" style="display: inline-block; width: 80px; height: auto;">
    </div>

    <h1 class="title" style="font-size: 24px; text-align: center;">Silakan verifikasi identitas Anda untuk mengatur ulang kata sandi.</h1>

    <div class="card" style="max-width: 42rem; margin: 0 auto; padding: 24px; border: 1px solid #808080; border-radius: 12px; text-align: left;">
        <p>Berikut adalah kode verifikasi untuk pengaturan ulang kata sandi Anda:</p>

        <h2 class="code" style="text-align: center; letter-spacing: 8px;">{{ $code }}</h2>

        <p>Kode ini berlaku selama <b>30 menit</b> dan hanya bisa digunakan satu kali.</p>

        <p><b>Mohon jangan bagikan kode ini kepada pihak mana pun.</b> Jika Anda tidak mengajukan permintaan ini, silakan abaikan pesan ini.</p>

        <p>
            Hormat kami,<br>
            JogjaCenter
        </p>
    </div>
</body>
</html>
