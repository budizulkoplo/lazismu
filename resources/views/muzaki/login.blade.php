<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Muzaki</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #fff7ed 0%, #ffffff 45%, #ffedd5 100%);
        }
        .portal-card {
            max-width: 430px;
            border: 0;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(252, 140, 4, 0.15);
        }
        .btn-brand {
            background: #fc8c04;
            border-color: #fc8c04;
            color: #fff;
        }
        .btn-brand:hover {
            background: #de7900;
            border-color: #de7900;
            color: #fff;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <div class="card portal-card w-100">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset($setting->path_logo ?? 'logo.png') }}" alt="Logo" style="height: 84px;" class="mx-auto mb-3">
                <h2 class="mb-1">Login Muzaki</h2>
                <p class="text-muted mb-0">Masuk cukup dengan NIK untuk melihat informasi pribadi dan transaksi personal muzaki.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('muzaki.login.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" class="form-control form-control-lg" maxlength="16" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK" required autofocus>
                </div>
                <button class="btn btn-brand btn-lg w-100">Masuk</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none text-muted">Kembali ke login admin</a>
            </div>
        </div>
    </div>
</body>
</html>
