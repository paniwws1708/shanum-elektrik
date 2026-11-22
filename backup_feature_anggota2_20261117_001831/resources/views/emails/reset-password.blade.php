<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Shanum Elektrik</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #2563eb; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; }
        .header p { color: #bfdbfe; margin: 8px 0 0; font-size: 14px; }
        .body { padding: 30px; }
        .body p { color: #374151; font-size: 14px; line-height: 1.6; }
        .btn { display: inline-block; background-color: #2563eb; color: #ffffff !important; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 14px; margin: 20px 0; }
        .btn:hover { background-color: #1d4ed8; }
        .footer { padding: 20px 30px; background-color: #f9fafb; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { color: #9ca3af; font-size: 12px; margin: 0; }
        .url-fallback { color: #6b7280; font-size: 12px; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚡ Shanum Elektrik</h1>
            <p>Reset Password</p>
        </div>
        <div class="body">
            <p>Halo,</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>

            <div style="text-align: center;">
                <a href="{{ url('/password/reset/' . $token . '?email=' . urlencode($email)) }}" class="btn">
                    Reset Password
                </a>
            </div>

            <p>Link reset password ini akan kadaluarsa dalam <strong>60 menit</strong>.</p>
            <p>Jika Anda tidak merasa meminta reset password, abaikan email ini.</p>

            <p class="url-fallback">
                Jika tombol tidak berfungsi, copy dan paste URL berikut ke browser Anda:<br>
                {{ url('/password/reset/' . $token . '?email=' . urlencode($email)) }}
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Shanum Elektrik. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
