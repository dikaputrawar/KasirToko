<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - WarungApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-success text-white">
                    <strong>WarungApp</strong>
                    <div class="small">Silakan masuk untuk melanjutkan</div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger small">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Masuk</button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3 text-muted small">
                Admin diarahkan ke Dashboard. Kasir diarahkan ke Kasir.
            </div>
        </div>
    </div>
</div>
</body>
</html>
