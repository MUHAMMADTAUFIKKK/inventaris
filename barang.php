<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); // Redirect ke halaman login
    exit(); // Hentikan eksekusi script
}

if (isset($_POST['tambah'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga_barang = (float)$_POST['harga_barang']; 
    $stok_bagus = (int)$_POST['stok_bagus'];
    $stok_rusak_ringan = (int)$_POST['stok_rusak_ringan'];
    $stok_rusak_berat = (int)$_POST['stok_rusak_berat'];
    $id_kategori = (int)$_POST['id_kategori'];

    // Pastikan Total dihitung
    $total = $stok_bagus + $stok_rusak_ringan + $stok_rusak_berat;

    // Periksa apakah kategori valid
    $cek_kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori = '$id_kategori'");
    if (mysqli_num_rows($cek_kategori) == 0) {
        die("Error: Kategori tidak ditemukan.");
    }

    $query = "INSERT INTO barang (nama_barang, harga_barang, stok_bagus, stok_rusak_ringan, stok_rusak_berat, id_kategori, Total) 
              VALUES ('$nama_barang', '$harga_barang', '$stok_bagus', '$stok_rusak_ringan', '$stok_rusak_berat', '$id_kategori', '$total')";

    if (mysqli_query($conn, $query)) {
        header("Location: barang.php");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

if (isset($_GET['hapus'])) {
    $id_barang = (int)$_GET['hapus'];

    // Periksa apakah barang ada di database sebelum menghapus
    $cek = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id_barang'");
    if (mysqli_num_rows($cek) > 0) {
        $delete = mysqli_query($conn, "DELETE FROM barang WHERE id_barang='$id_barang'");
        if ($delete) {
            header("Location: barang.php?success=hapus");
            exit();
        } else {
            echo "<script>alert('Gagal menghapus barang.'); window.location.href='barang.php';</script>";
        }
    } else {
        echo "<script>alert('Barang tidak ditemukan.'); window.location.href='barang.php';</script>";
    }
}

// Edit Barang
if (isset($_POST['edit'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $stok_bagus = $_POST['stok_bagus'];
    $stok_rusak_ringan = $_POST['stok_rusak_ringan'];
    $stok_rusak_berat = $_POST['stok_rusak_berat'];
    $id_kategori = $_POST['id_kategori'];

    $query = "UPDATE barang SET nama_barang='$nama_barang', harga_barang='$harga_barang', 
              stok_bagus='$stok_bagus', stok_rusak_ringan='$stok_rusak_ringan', stok_rusak_berat='$stok_rusak_berat', id_kategori='$id_kategori' 
              WHERE id_barang='$id_barang'";
    mysqli_query($conn, $query);
    header("Location: barang.php");
}

// Ambil data barang dengan join ke kategori
$result = mysqli_query($conn, "SELECT barang.*, kategori.nama_kategori 
                               FROM barang 
                               JOIN kategori ON barang.id_kategori = kategori.id_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a, #2563eb); /* Gradient background */
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0.5, 0.2);
            margin-top: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #1e3a8a; /* Dark blue */
            font-weight: 600;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg,rgb(80, 92, 128),rgb(27, 122, 167)); /* Gradient blue */
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px; 
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #9ca3af); /* Gradient gray */
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #f87171); /* Gradient red */
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24); /* Gradient yellow */
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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
            background: rgba(218, 218, 218, 0.1); /* Light hover effect */
        }

        .modal-content {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-body label {
            color: #333; /* Warna gelap untuk label */
            font-weight: 500;
        }

        .modal-body .form-control {
            color: #333; /* Warna gelap untuk input */
        }

        .modal-body .form-control::placeholder {
            color: #999; /* Warna placeholder yang lebih lembut */
        }

        /* Warna font pada modal header */
        .modal-header {
            color: #fff; /* Warna putih untuk teks di header modal */
        }

        .modal-header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb); /* Gradient blue */
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .modal-body {
            padding: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 5px rgba(30, 58, 138, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manajemen Barang</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahbarang">Tambah Barang</button><br>
        <a href="agregasi_barang.php" class="btn btn-secondary">Detail Barang</a>
        <!-- Tabel Barang -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok Bagus</th>
                    <th>Stok Rusak Ringan</th>
                    <th>Stok Rusak Berat</th>
                    <th>Kategori</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['harga_barang'] ?></td>
                    <td><?= $row['stok_bagus'] ?></td>
                    <td><?= $row['stok_rusak_ringan'] ?></td>
                    <td><?= $row['stok_rusak_berat'] ?></td>
                    <td><?= $row['nama_kategori'] ?></td>
                    <td><?= $row['stok_bagus'] + $row['stok_rusak_ringan'] + $row['stok_rusak_berat'] ?></td>
                    <td>
                        <a href="barang.php?hapus=<?= $row['id_barang'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBarang<?= $row['id_barang'] ?>">Edit</button>
                    </td>
                </tr>

                <!-- Modal Edit Barang -->
                <div class="modal fade" id="editBarang<?= $row['id_barang'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_barang" value="<?= $row['id_barang'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Barang</label>
                                        <input type="text" class="form-control" name="nama_barang" value="<?= $row['nama_barang'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Harga Barang</label>
                                        <input type="number" class="form-control" name="harga_barang" value="<?= $row['harga_barang'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stok Bagus</label>
                                        <input type="number" class="form-control" name="stok_bagus" value="<?= $row['stok_bagus'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stok Rusak Ringan</label>
                                        <input type="number" class="form-control" name="stok_rusak_ringan" value="<?= $row['stok_rusak_ringan'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stok Rusak Berat</label>
                                        <input type="number" class="form-control" name="stok_rusak_berat" value="<?= $row['stok_rusak_berat'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-control" name="id_kategori" required>
                                            <?php
                                            $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                                            while ($kat = mysqli_fetch_assoc($kategori)) {
                                                $selected = ($kat['id_kategori'] == $row['id_kategori']) ? 'selected' : '';
                                                echo "<option value='{$kat['id_kategori']}' $selected>{$kat['nama_kategori']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </tbody>
        </table>

        <!-- Modal Tambah Barang -->
        <div class="modal fade" id="tambahbarang" tabindex="-1" aria-labelledby="tambahbarangLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Barang</label>
                                <input type="number" class="form-control" name="harga_barang" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok Bagus</label>
                                <input type="number" class="form-control" name="stok_bagus" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok Rusak Ringan</label>
                                <input type="number" class="form-control" name="stok_rusak_ringan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok Rusak Berat</label>
                                <input type="number" class="form-control" name="stok_rusak_berat" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" name="id_kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                                    while ($kat = mysqli_fetch_assoc($kategori)) {
                                        echo "<option value='{$kat['id_kategori']}'>{$kat['nama_kategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="tambah" class="btn btn-primary">Tambah Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <a href="dashboard.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>