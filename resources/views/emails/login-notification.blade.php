<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #667eea;
        }
        .info-value {
            color: #555;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #888;
        }
        .icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 12px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔐</div>
            <h1>Notifikasi Login</h1>
            <p>Si Bakat Hebat</p>
        </div>

        <div class="content">
            <div class="greeting">
                Hai {{ $userName }}! 👋
            </div>

            <p>Akun Anda telah berhasil login ke platform Si Bakat Hebat. Berikut adalah detail login Anda:</p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">📅 Waktu Login:</span>
                    <span class="info-value">{{ $loginTime }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🌐 Alamat IP:</span>
                    <span class="info-value">{{ $ipAddress }}</span>
                </div>
            </div>

            <div class="alert">
                <strong>⚠️ Penting:</strong> Jika Anda tidak melakukan login ini, segera ubah password akun Anda atau hubungi admin.
            </div>

            <p style="margin-top: 20px; color: #666;">
                Email ini dikirim secara otomatis untuk keamanan akun Anda. Jangan reply ke email ini.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">© {{ date('Y') }} Si Bakat Hebat. Hak Cipta Dilindungi.</p>
            <p style="margin: 5px 0 0 0;">Platform Observasi Karakter TB40</p>
        </div>
    </div>
</body>
</html>
