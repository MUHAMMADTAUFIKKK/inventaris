<?php
session_start();
include 'koneksi.php'; // File koneksi ke database

// Tambah Kategori
if (isset($_POST['tambah'])) {
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO kategori (nama_kategori, deskripsi) 
              VALUES ('$nama_kategori', '$deskripsi')";
    mysqli_query($conn, $query);
    header("Location: kategori.php");
}

// Hapus Kategori
if (isset($_GET['hapus'])) {
    $id_kategori = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id_kategori'");
    header("Location: kategori.php");
}

// Edit Kategori
if (isset($_POST['edit'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];

    $query = "UPDATE kategori SET nama_kategori='$nama_kategori', deskripsi='$deskripsi' 
              WHERE id_kategori='$id_kategori'";
    mysqli_query($conn, $query);
    header("Location: kategori.php");
}

// Ambil data kategori
$result = mysqli_query($conn, "SELECT * FROM kategori");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Manajemen Kategori</h2>
        
        <!-- Tombol Tambah Kategori -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahKategori">Tambah Kategori</button>

        <!-- Tabel Kategori -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id_kategori'] ?></td>
                    <td><?= $row['nama_kategori'] ?></td>
                    <td><?= $row['deskripsi'] ?></td>
                    <td>
                        <a href="kategori.php?hapus=<?= $row['id_kategori'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKategori<?= $row['id_kategori'] ?>">Edit</button>
                    </td>
                </tr>

                <!-- Modal Edit Kategori -->
                <div class="modal fade" id="editKategori<?= $row['id_kategori'] ?>" tabindex="-1" aria-labelledby="editKategoriLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Kategori</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_kategori" value="<?= $row['id_kategori'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kategori</label>
                                        <input type="text" class="form-control" name="nama_kategori" value="<?= $row['nama_kategori'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" required><?= $row['deskripsi'] ?></textarea>
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

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="tambahKategori" tabindex="-1" aria-labelledby="tambahKategoriLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" required></textarea>
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
