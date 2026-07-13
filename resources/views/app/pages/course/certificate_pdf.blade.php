<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page {
        size: landscape;
        margin: 0;
    }
    body {
        margin: 0;
        padding: 0;
        font-family: "DejaVu Serif", serif;
        color: #181c1d;
    }
    .cert-wrap {
        padding: 26px;
    }
    .cert-border {
        border: 3px solid {{ $accent }};
        padding: 40px 60px;
        text-align: center;
    }
    .cert-border-inner {
        border: 1px solid #dfe3e3;
        padding: 30px 40px;
    }
    .cert-eyebrow {
        font-family: "DejaVu Sans", sans-serif;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 11px;
        color: {{ $accent }};
        font-weight: bold;
        margin-bottom: 6px;
    }
    .cert-title {
        font-size: 30px;
        line-height: 1.3;
        margin: 6px 0 18px;
        color: #181c1d;
    }
    .cert-intro {
        font-size: 13px;
        color: #475569;
        margin: 0 0 8px;
    }
    .cert-name {
        font-size: 32px;
        color: {{ $accent }};
        margin: 6px 0 18px;
        padding-bottom: 8px;
        border-bottom: 2px solid {{ $accent }};
        display: inline-block;
    }
    .cert-body {
        font-size: 13px;
        color: #475569;
        max-width: 600px;
        margin: 0 auto 14px;
        line-height: 1.6;
    }
    .cert-disclaimer {
        font-size: 10px;
        font-style: italic;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto 20px;
        line-height: 1.5;
    }
    .cert-footer {
        margin-top: 24px;
        padding-top: 14px;
        border-top: 1px solid #e2e8f0;
        font-size: 11px;
        color: #475569;
        width: 100%;
    }
    .cert-footer td {
        width: 50%;
        vertical-align: top;
    }
    .cert-footer .label {
        font-weight: bold;
        color: #181c1d;
    }
</style>
</head>
<body>
    <div class="cert-wrap">
        <div class="cert-border">
            <div class="cert-border-inner">
                <div class="cert-eyebrow">{{ $eyebrow }}</div>
                <div class="cert-title">{{ $title }}</div>
                <div class="cert-intro">{{ $intro }}</div>
                <div class="cert-name">{{ $name }}</div>
                <div class="cert-body">{{ $body }}</div>
                @if (!empty($disclaimer))
                    <div class="cert-disclaimer">{{ $disclaimer }}</div>
                @endif
                <table class="cert-footer">
                    <tr>
                        <td>
                            <div class="label">Date issued</div>
                            {{ $date }}
                        </td>
                        <td style="text-align:right;">
                            <div class="label">{{ $issuerName }}</div>
                            {{ $issuerEmail }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
