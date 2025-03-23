<?php
include 'koneksi.php';

// Hitung total peminjaman
$total_peminjaman_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman");
$total_peminjaman = mysqli_fetch_assoc($total_peminjaman_query)['total'];

// Hitung peminjaman yang masih dipinjam
$dipinjam_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status_peminjaman = 'Dipinjam'");
$dipinjam = mysqli_fetch_assoc($dipinjam_query)['total'];

// Hitung peminjaman yang sudah kembali
$kembali_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status_peminjaman = 'Kembali'");
$kembali = mysqli_fetch_assoc($kembali_query)['total'];

// Hitung peminjaman yang telat dikembalikan
$telat_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status_peminjaman = 'Telat'");
$telat = mysqli_fetch_assoc($telat_query)['total'];

// Hitung peminjaman yang hilang
$hilang_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status_peminjaman = 'Hilang'");
$hilang = mysqli_fetch_assoc($hilang_query)['total'];

// Ambil data agregasi nama barang yang dipinjam dan total barang dipinjam
$agregasi_query = mysqli_query($conn, "
    SELECT b.nama_barang, COUNT(p.id_peminjaman) AS total_dipinjam
    FROM peminjaman p
    JOIN barang b ON p.id_barang = b.id_barang
    GROUP BY b.nama_barang
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a, #2563eb); /* Gradient background */
            color: #333;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2, h3 {
            color: #1e3a8a; /* Dark blue */
            font-weight: 600;
        }

        .table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: linear-gradient(135deg, #1e3a8a, #2563eb); /* Gradient blue */
            color: white;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(37, 99, 235, 0.1); /* Light hover effect */
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: white;
            display: inline-block;
        }

        .status-selesai { background: #28a745; } /* Hijau */
        .status-dipinjam { background: #007bff; } /* Biru */
        .status-kembali { background: #ffc107; color: black; } /* Kuning */
        .status-telat { background: #fd7e14; } /* Oranye */
        .status-hilang { background: #dc3545; } /* Merah */
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Detail Peminjaman</h2>

        <!-- Tabel Statistik Peminjaman -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Peminjaman</td>
                    <td><?= $total_peminjaman ?></td>
                </tr>
                <tr>
                    <td>Dipinjam</td>
                    <td><?= $dipinjam ?></td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td><?= $kembali ?></td>
                </tr>
                <tr>
                    <td>Telat</td>
                    <td><?= $telat ?></td>
                </tr>
                <tr>
                    <td>Hilang</td>
                    <td><?= $hilang ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Tabel Agregasi Barang Dipinjam -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Total Yang Sudah Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($agregasi = mysqli_fetch_assoc($agregasi_query)) { ?>
                <tr>
                    <td><?= $agregasi['nama_barang'] ?></td>
                    <td><?= $agregasi['total_dipinjam'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="peminjaman.php" class="btn btn-secondary">← Kembali ke Fitur Peminjaman</a>

    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>