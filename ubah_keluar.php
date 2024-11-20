<?php
// Koneksi ke database
require 'function.php';

// Inisialisasi variabel
$id = $Nama = $jenis = $Tanggal_keluar = $jumlah = $harga = "";

// Ambil data barang berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM keluar WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $Nama = $row['Nama'];
        $jenis = $row['jenis'];
        $Tanggal_keluar = $row['Tanggal_keluar'];
        $jumlah = $row['jumlah'];
        $harga = $row['harga'];
    }
    $stmt->close();
}

// Untuk mengubah data barang
// Cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
    // Ambil data dari setiap elemen dalam form
    $id = $_POST["id"];
    $Nama = $_POST["Nama"];
    $jenis = $_POST["jenis"];
    $Tanggal_keluar = $_POST["Tanggal_keluar"];
    $jumlah = $_POST["jumlah"];
    $harga = $_POST["harga"];

    // Validasi input
    $errors = [];
    if (empty($Tanggal_keluar)) {
        $errors[] = "Tanggal keluar tidak boleh kosong.";
    }

    if ($harga < 0 || $harga > 2147483647) { // Sesuaikan batas atas sesuai tipe data kolom
        $errors[] = "Harga harus dalam rentang yang valid.";
    }

    if (empty($errors)) {
        // Siapkan pernyataan update
        $stmt = $conn->prepare("UPDATE keluar SET Nama = ?, jenis = ?, Tanggal_keluar = ?, jumlah = ?, harga = ? WHERE id = ?");
        $stmt->bind_param("sssiii", $Nama, $jenis, $Tanggal_keluar, $jumlah, $harga, $id);

        // Eksekusi query dan cek apakah ada error
        if ($stmt->execute()) {
            // Tampilkan pesan sukses
            echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;'>Data berhasil diubah!</div>";
        } else {
            // Tampilkan pesan error
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Error: " . $stmt->error . "</div>";
        }

        // Tutup statement
        $stmt->close();
    } else {
        // Tampilkan pesan error
        foreach ($errors as $error) {
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Error: " . $error . "</div>";
        }
    }
}

// Tutup koneksi
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data</title>
    <link rel="stylesheet" href="add_data.css">
</head>
<body>
    <div class="form-container">
        <h1>Ubah Barang Keluar</h1>
        <form action="" method="post">
            <ul>
                <li>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                </li>
                <li>
                    <label for="Nama">Nama:</label>
                    <input type="text" name="Nama" id="Nama" value="<?php echo $Nama; ?>" required>
                </li>
                <li>
                    <label for="jenis">Jenis:</label>
                    <input type="text" name="jenis" id="jenis" value="<?php echo $jenis; ?>" required>
                </li>
                <li>
                    <label for="Tanggal_keluar">Tanggal Keluar:</label>
                    <input type="date" name="Tanggal_keluar" id="Tanggal_keluar" value="<?php echo $Tanggal_keluar; ?>" required>
                </li>
                <li>
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="jumlah" id="jumlah" value="<?php echo $jumlah; ?>" required>
                </li>
                <li>
                    <label for="harga">Harga:</label>
                    <input type="number" name="harga" id="harga" value="<?php echo $harga; ?>" required>
                </li>
                <li>
                    <button type="submit" name="submit">Ubah Data</button>
                </li>
            </ul>

            <div class="back-container">
                <a href="keluar.php" class="back-btn">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
