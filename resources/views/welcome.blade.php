<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WarungKu - Aplikasi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .welcome-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .welcome-body {
            padding: 40px 30px;
            text-align: center;
        }
        .btn-welcome {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-welcome:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
        }
        .feature-list {
            text-align: left;
            margin: 20px 0;
        }
        .feature-list li {
            margin: 10px 0;
            color: #666;
        }
        .feature-list i {
            color: #28a745;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <div class="welcome-header">
            <i class="fas fa-store fa-3x mb-3"></i>
            <h1 class="mb-2">WarungKu</h1>
            <p class="mb-0">Aplikasi Kasir Modern & Terpercaya</p>
        </div>
        <div class="welcome-body">
            <h4 class="mb-4">Selamat Datang di WarungKu</h4>
            <p class="text-muted mb-4">
                Sistem manajemen kasir yang mudah digunakan untuk warung dan toko kecil Anda.
            </p>
            
            <div class="feature-list">
                <ul class="list-unstyled">
                    <li><i class="fas fa-check-circle"></i> Transaksi kasir yang cepat</li>
                    <li><i class="fas fa-check-circle"></i> Manajemen stok otomatis</li>
                    <li><i class="fas fa-check-circle"></i> Laporan penjualan detail</li>
                    <li><i class="fas fa-check-circle"></i> Cetak resi otomatis</li>
                    <li><i class="fas fa-check-circle"></i> Analisis bisnis</li>
                </ul>
            </div>
            
            <a href="{{ route('dashboard.index') }}" class="btn-welcome">
                <i class="fas fa-arrow-right me-2"></i>
                Mulai Menggunakan
            </a>
        </div>
    </div>
</body>
</html>
