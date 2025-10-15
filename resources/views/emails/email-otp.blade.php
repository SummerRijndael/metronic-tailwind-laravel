{{-- resources/views/mail/email-otp.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One-Time Verification Code</title>
    <style>
        /* Base Styles for better cross-client compatibility */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-top: 4px solid #007bff;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .otp-box {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 6px;
            border: 1px dashed #ced4da;
        }

        .otp-code {
            font-size: 32px;
            color: #007bff;
            font-weight: bold;
            margin: 0;
            letter-spacing: 5px;
        }

        .disclaimer {
            font-size: 14px;
            color: #6c757d;
            margin-top: 30px;
            line-height: 1.5;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #adb5bd;
        }

        /* Responsive adjustments */
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1 style="font-size: 24px; color: #343a40;">Account Verification</h1>
        </div>

        <p style="font-size: 16px; color: #495057;">
            Hello,
        </p>

        <p style="font-size: 16px; color: #495057;">
            To complete your request, please use the verification code below. This code is required to confirm your
            identity.
        </p>

        <div class="otp-box">
            <p style="font-size: 14px; color: #6c757d; margin-bottom: 10px;">Your One-Time Code</p>
            <h2 class="otp-code">{{ $otpCode }}</h2>
        </div>

        <p style="font-size: 16px; color: #dc3545; font-weight: 500;">
            <span style="font-weight: bold;">Important:</span> This code will expire in 5 minutes.
        </p>

        <div class="disclaimer">
            <p>
                If you did not request this code, please ignore this email. Your password and account security remain
                intact.
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} [Your Company Name]. All rights reserved.
            </p>
        </div>
    </div>

</body>

</html>
