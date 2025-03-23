<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregasi Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Data Detail Barang</h2>

        <!-- Rata-rata Harga Barang per Kategori -->
        <div class="card mb-3">
            <div class="card-body">
                <h5>Rata-rata Harga Barang per Kategori</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Rata-rata Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT k.nama_kategori, AVG(b.harga_barang) AS rata_rata_harga
                                  FROM barang b
                                  JOIN kategori k ON b.id_kategori = k.id_kategori
                                  GROUP BY k.nama_kategori";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['nama_kategori']}</td>
                                    <td>Rp " . number_format($row['rata_rata_harga'], 2, ',', '.') . "</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Nilai Barang -->
        <div class="card mb-3">
            <div class="card-body">
                <h5>Total Nilai Barang</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Total Nilai (Stok Harga)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT nama_barang, 
                                         (stok_bagus + stok_rusak_ringan + stok_rusak_berat) * harga_barang AS total_nilai
                                  FROM barang";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['nama_barang']}</td>
                                    <td>Rp " . number_format($row['total_nilai'], 2, ',', '.') . "</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="barang.php" class="btn btn-secondary">← Kembali ke Fitur Barang</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
