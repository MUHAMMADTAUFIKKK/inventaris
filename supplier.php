<?php
session_start();
include 'koneksi.php'; // File koneksi ke database
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); // Redirect ke halaman login
    exit(); // Hentikan eksekusi script
}

// Tambah Supplier
if (isset($_POST['tambah'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];

    $query = "INSERT INTO supplier (nama_supplier, alamat, no_telp) 
              VALUES ('$nama_supplier', '$alamat', '$no_telp')";
    mysqli_query($conn, $query);
    header("Location: supplier.php");
}

// Hapus Supplier
if (isset($_GET['hapus'])) {
    $id_supplier = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM supplier WHERE id_supplier='$id_supplier'");
    header("Location: supplier.php");
}

// Edit Supplier
if (isset($_POST['edit'])) {
    $id_supplier = $_POST['id_supplier'];
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];

    $query = "UPDATE supplier SET nama_supplier='$nama_supplier', alamat='$alamat', no_telp='$no_telp' 
              WHERE id_supplier='$id_supplier'";
    mysqli_query($conn, $query);
    header("Location: supplier.php");
}

// Ambil data supplier
$result = mysqli_query($conn, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Supplier</title>
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

        .btn-success {
            background: linear-gradient(135deg, #28a745, #34d399); /* Gradient green */
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
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

        /* Warna font pada form tambah dan edit */
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
        <h2>Manajemen Supplier</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahSupplier">Tambah Supplier</button>

        <!-- Tabel Supplier -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['nama_supplier'] ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td><?= $row['no_telp'] ?></td>
                    <td>
                        <a href="supplier.php?hapus=<?= $row['id_supplier'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSupplier<?= $row['id_supplier'] ?>">Edit</button>
                    </td>
                </tr>

                <!-- Modal Edit Supplier -->
                <div class="modal fade" id="editSupplier<?= $row['id_supplier'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Supplier</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_supplier" value="<?= $row['id_supplier'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Supplier</label>
                                        <input type="text" class="form-control" name="nama_supplier" value="<?= $row['nama_supplier'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control" name="alamat" required><?= $row['alamat'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" class="form-control" name="no_telp" value="<?= $row['no_telp'] ?>" required>
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

    <!-- Modal Tambah Supplier -->
    <div class="modal fade" id="tambahSupplier" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" name="nama_supplier" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="no_telp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
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