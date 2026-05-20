<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            text-align: center;
        }

        h1 {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 28px;
        }

        p {
            color: #cbd5f5;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .menu {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            flex: 1 1 250px;
            max-width: 300px;
            padding: 25px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255,255,255,0.1);
            transition: 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.1);
        }

        .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .scan {
            color: #38bdf8;
        }

        .data {
            color: #34d399;
        }

        a {
            text-decoration: none;
            color: white;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #94a3b8;
        }

        .logout {
            margin-top: 20px;
        }

        .btn-logout {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            background: #ef4444;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
        }

        .btn-logout:hover {
            background: #dc2626;
        }

        /* 🔥 RESPONSIVE MOBILE */
        @media (max-width: 600px) {
            h1 {
                font-size: 22px;
            }

            p {
                font-size: 13px;
            }

            .card {
                padding: 20px;
            }

            .icon {
                font-size: 35px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <h1>📊 Sistem Inventori</h1>
    <p>Halo, {{ auth()->user()->name }} 👋</p>

    <div class="menu">

        <!-- SCAN -->
        <a href="{{ route('scan.index') }}">
            <div class="card">
                <div class="icon scan">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h3>Scan Barang</h3>
                <p>Scan QR untuk cek data</p>
            </div>
        </a>

        <!-- DATA -->
        <a href="{{ route('sarpra.dashboard') }}">
            <div class="card">
                <div class="icon data">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>Data Inventori</h3>
                <p>Lihat & kelola barang</p>
            </div>
        </a>

    </div>

    <!-- Logout -->
    <div class="logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn-logout">Logout</button>
        </form>
    </div>

    <div class="footer">
        © {{ date('Y') }} Sistem Inventori Sekolah
    </div>

</div>

</body>
</html>