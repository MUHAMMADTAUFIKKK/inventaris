<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); // Redirect ke halaman login
    exit(); // Hentikan eksekusi script
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        /* Layout utama */
        body {
            background: linear-gradient(135deg,rgb(57, 70, 107), #2563eb); /* Gradient background */
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar */
        .navbar {
            background: rgba(241, 236, 236, 0.9); /* Semi-transparent white */
            box-shadow: 0 4px 10px rgba(44, 69, 117, 0.88);
            padding: 10px 20px;
        }

        .navbar-brand {
            font-size: 26px;
            font-weight: 600;
            color:rgb(39, 68, 212) !important; /* Dark blue */
        }

        .navbar-nav .nav-link {
            color:rgb(29, 57, 134) !important; /* Dark blue */
            font-weight: 800;
            margin-left: 10px;
            margin-right:20px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color:rgb(252, 252, 252) !important; /* Light blue */
        }

        /* Konten Utama */
        .content {
            padding: 15px;
            margin-top: 100px; /* Sesuaikan dengan tinggi navbar */
        }

        /* Kartu Statistik */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card.bg-primary {
            background: linear-gradient(135deg,rgb(58, 199, 15),rgb(85, 175, 49)); /* Gradient blue */
        }

        .card.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24); /* Gradient yellow */
        }

        .card.bg-danger {
            background: linear-gradient(135deg, #ef4444, #f87171); /* Gradient red */
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 24px;
            font-weight: 700;
        }

        /* Tombol Logout */
        .btn-logout {
            background: linear-gradient(135deg, #ef4444, #f87171); /* Gradient red */
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #f87171, #ef4444); /* Reverse gradient */
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                margin-left: 0;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inventaris Sekolah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="barang.php">Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="supplier.php">Supplier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-logout" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card bg-primary text-white p-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Barang</h5>
                            <p class="card-text">
                                <?php 
                                $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM barang");
                                $data = mysqli_fetch_assoc($result);
                                echo $data['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-warning text-dark p-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Supplier</h5>
                            <p class="card-text">
                                <?php 
                                $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM supplier");
                                $data = mysqli_fetch_assoc($result);
                                echo $data['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-danger text-white p-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Peminjaman</h5>
                            <p class="card-text">
                                <?php 
                                $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman");
                                $data = mysqli_fetch_assoc($result);
                                echo $data['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>