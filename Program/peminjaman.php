<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); // Redirect ke halaman login
    exit(); // Hentikan eksekusi script
}

// Tambah Peminjaman
if (isset($_POST['tambah'])) {
    $id_barang = $_POST['id_barang'];
    $peminjam = $_POST['peminjam'];
    $alasan = $_POST['alasan'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status_peminjaman = 'Dipinjam'; // Status default

    $query = "INSERT INTO peminjaman (id_barang, peminjam, alasan, tanggal_pinjam, tanggal_kembali, status_peminjaman) 
              VALUES ('$id_barang', '$peminjam', '$alasan', '$tanggal_pinjam', '$tanggal_kembali', '$status_peminjaman')";
    mysqli_query($conn, $query);
    header("Location: peminjaman.php");
}

// Ambil data barang untuk dropdown
$barang_query = mysqli_query($conn, "SELECT * FROM barang");

// Hapus Peminjaman
if (isset($_GET['hapus'])) {
    $id_peminjaman = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM peminjaman WHERE id_peminjaman='$id_peminjaman'");
    header("Location: peminjaman.php");
}

// Edit Peminjaman
if (isset($_POST['edit'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $id_barang = $_POST['id_barang'];
    $peminjam = $_POST['peminjam'];
    $alasan = $_POST['alasan'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status_peminjaman = $_POST['status_peminjaman'];

    $query = "UPDATE peminjaman SET id_barang='$id_barang', peminjam='$peminjam', alasan='$alasan', 
              tanggal_pinjam='$tanggal_pinjam', tanggal_kembali='$tanggal_kembali', status_peminjaman='$status_peminjaman' 
              WHERE id_peminjaman='$id_peminjaman'";
    mysqli_query($conn, $query);
    header("Location: peminjaman.php");
}

// Ambil data peminjaman (JOIN untuk mendapatkan nama barang)
$result = mysqli_query($conn, "SELECT p.*, b.nama_barang 
                               FROM peminjaman p
                               JOIN barang b ON p.id_barang = b.id_barang");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Peminjaman</title>
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
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
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
            background: linear-gradient(135deg, #1e3a8a, #2563eb); /* Gradient blue */
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
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
            padding: 10px 20px;
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

        .status-selesai { background: #28a745; 
        border-radius:50px;} /* Hijau */
        .status-dipinjam { background: #007bff; border-radius:50px;} /* Biru */
        .status-kembali { background: #ffc107; color: black; border-radius:50px;} /* Kuning */
        .status-telat { background: #fd7e14; border-radius:50px;} /* Oranye */
        .status-hilang { background: #dc3545;border-radius:50px; } /* Merah */

        .modal-content {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Manajemen Peminjaman</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPeminjaman">Tambah Peminjaman</button>
        <br><a href="agregasi_peminjaman.php" class="btn btn-secondary">Detail Peminjaman</a>
        <!-- Tabel Peminjaman -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Peminjam</th>
                    <th>Alasan</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['peminjam'] ?></td>
                    <td><?= $row['alasan'] ?></td>
                    <td><?= $row['tanggal_pinjam'] ?></td>
                    <td><?= $row['tanggal_kembali'] ?></td>
                    <td>
                        <span class="status-badge status-<?= strtolower($row['status_peminjaman']) ?>">
                            <?= $row['status_peminjaman'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="peminjaman.php?hapus=<?= $row['id_peminjaman'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPeminjaman<?= $row['id_peminjaman'] ?>">Edit</button>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editPeminjaman<?= $row['id_peminjaman'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Peminjaman</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                    <div class="mb-3">
                                        <label>Nama Barang</label>
                                        <select class="form-control" name="id_barang" required>
                                            <?php
                                            $barang_query = mysqli_query($conn, "SELECT * FROM barang");
                                            while ($barang = mysqli_fetch_assoc($barang_query)) {
                                                $selected = ($barang['id_barang'] == $row['id_barang']) ? 'selected' : '';
                                                echo "<option value='{$barang['id_barang']}' $selected>{$barang['nama_barang']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Peminjam</label>
                                        <input type="text" class="form-control" name="peminjam" value="<?= $row['peminjam'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Alasan</label>
                                        <textarea class="form-control" name="alasan" required><?= $row['alasan'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Tanggal Pinjam</label>
                                        <input type="date" class="form-control" name="tanggal_pinjam" value="<?= $row['tanggal_pinjam'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Tanggal Kembali</label>
                                        <input type="date" class="form-control" name="tanggal_kembali" value="<?= $row['tanggal_kembali'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status Peminjaman</label>
                                        <select class="form-control" name="status_peminjaman">
                                            <option value="Selesai" <?= ($row['status_peminjaman'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                                            <option value="Dipinjam" <?= ($row['status_peminjaman'] == 'Dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                                            <option value="Kembali" <?= ($row['status_peminjaman'] == 'Kembali') ? 'selected' : '' ?>>Kembali</option>
                                            <option value="Telat" <?= ($row['status_peminjaman'] == 'Telat') ? 'selected' : '' ?>>Telat</option>
                                            <option value="Hilang" <?= ($row['status_peminjaman'] == 'Hilang') ? 'selected' : '' ?>>Hilang</option>
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
    </div>

    <!-- Modal Tambah Peminjaman -->
    <div class="modal fade" id="tambahPeminjaman" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Barang</label>
                            <select class="form-control" name="id_barang" required>
                                <?php while ($barang = mysqli_fetch_assoc($barang_query)) { ?>
                                    <option value="<?= $barang['id_barang'] ?>"><?= $barang['nama_barang'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Peminjam</label>
                            <input type="text" class="form-control" name="peminjam" required>
                        </div>
                        <div class="mb-3">
                            <label>Alasan</label>
                            <textarea class="form-control" name="alasan" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Pinjam</label>
                            <input type="date" class="form-control" name="tanggal_pinjam" required>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Kembali</label>
                            <input type="date" class="form-control" name="tanggal_kembali" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control" name="status_peminjaman">
                                <option value="Dipinjam">Dipinjam</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <a href="dashboard.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>