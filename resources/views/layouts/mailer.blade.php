<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @section('styles')
            <style type="text/css">
                body {
                    margin: 0;
                    padding: 24px 12px;
                    background-color: #f5f7fb;
                    color: #1f2937;
                    font-family: Arial, Helvetica, sans-serif;
                    line-height: 1.6;
                }

                table {
                    border-collapse: collapse;
                }

                .email-shell {
                    width: 100%;
                    background-color: #f5f7fb;
                }

                .email-card {
                    width: 100%;
                    max-width: 640px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    border: 1px solid #d7deea;
                }

                .email-header {
                    padding: 24px 28px 12px;
                    font-size: 20px;
                    font-weight: 700;
                    color: #0f172a;
                }

                .email-content {
                    padding: 0 28px 28px;
                    font-size: 15px;
                }

                .email-content p {
                    margin: 0 0 16px;
                }

                .email-content a {
                    color: #2f80ed;
                }

                .email-button {
                    display: inline-block;
                    padding: 12px 20px;
                    border-radius: 4px;
                    background-color: #2f80ed;
                    color: #ffffff !important;
                    font-weight: 700;
                    text-decoration: none;
                }

                .email-link {
                    word-break: break-all;
                }

                .email-note {
                    color: #475569;
                    font-size: 14px;
                }

                .details-table {
                    width: 100%;
                    margin: 16px 0;
                    border: 1px solid #d7deea;
                }

                .details-table td,
                .details-table th {
                    padding: 10px 12px;
                    border: 1px solid #d7deea;
                    vertical-align: top;
                    text-align: left;
                }

                .details-table th,
                .details-label {
                    width: 40%;
                    font-weight: 700;
                    background-color: #f8fafc;
                }

                .email-footer {
                    padding-top: 8px;
                    color: #475569;
                    font-size: 14px;
                }
            </style>
        @show
    </head>
    <body>
        <table role="presentation" class="email-shell">
            <tr>
                <td align="center">
                    <table role="presentation" class="email-card">
                        <tr>
                            <td class="email-header">{{ env('APP_TITLE') }}</td>
                        </tr>
                        <tr>
                            <td class="email-content">
                                @yield('content')
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
