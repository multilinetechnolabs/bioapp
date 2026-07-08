<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Private Preview</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top right, #e8f4f4, transparent 40%),
                radial-gradient(circle at top left, #f0fdfa, transparent 40%),
                #f6fafa;
            color: #181c1d;
            padding: 20px;
        }
        .gate-card {
            width: 100%;
            max-width: 380px;
            background: #fff;
            border: 1px solid #dfe3e3;
            border-radius: 18px;
            padding: 32px 28px;
            box-shadow: 0 20px 50px rgba(15, 118, 110, .12);
            text-align: center;
        }
        .gate-card__icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            color: #0f766e;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 1.3rem;
        }
        .gate-card h1 {
            font-size: 1.15rem;
            margin: 0 0 6px;
            color: #181c1d;
        }
        .gate-card p {
            font-size: .84rem;
            color: #475569;
            margin: 0 0 20px;
        }
        .gate-card input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1.5px solid #dfe3e3;
            font-size: .95rem;
            margin-bottom: 12px;
        }
        .gate-card input[type="password"]:focus {
            outline: none;
            border-color: #14b8a6;
        }
        .gate-card button {
            width: 100%;
            padding: 11px 14px;
            border-radius: 999px;
            border: none;
            background: #0d9488;
            color: #fff;
            font-weight: 600;
            font-size: .92rem;
            cursor: pointer;
        }
        .gate-card button:hover { background: #009b7d; }
        .gate-card button[disabled] { opacity: .6; cursor: not-allowed; }
        .gate-error {
            display: none;
            color: #b91c1c;
            font-size: .8rem;
            margin: -4px 0 12px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="gate-card">
        <div class="gate-card__icon">&#128274;</div>
        <h1>Private Preview</h1>
        <p>This is a testing/staging environment. Enter the access password to continue.</p>
        <form id="gateForm" autocomplete="off">
            <div class="gate-error" id="gateError">Incorrect password. Please try again.</div>
            <input type="password" name="password" id="gatePassword" placeholder="Password" autofocus required>
            <button type="submit" id="gateSubmit">Enter</button>
        </form>
    </div>

    <script>
    (function () {
        var form = document.getElementById('gateForm');
        var errorEl = document.getElementById('gateError');
        var submitBtn = document.getElementById('gateSubmit');
        var input = document.getElementById('gatePassword');
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            errorEl.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.textContent = 'Checking...';

            fetch('{{ route('site-access.verify') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: 'password=' + encodeURIComponent(input.value)
            })
                .then(function (res) { return res.json().then(function (data) { return { status: res.status, data: data }; }); })
                .then(function (result) {
                    if (result.data && result.data.ok) {
                        window.location.reload();
                        return;
                    }
                    errorEl.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Enter';
                    input.value = '';
                    input.focus();
                })
                .catch(function () {
                    errorEl.textContent = 'Something went wrong. Please try again.';
                    errorEl.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Enter';
                });
        });
    })();
    </script>
</body>
</html>
