<?php
// Koneksi ke database
require 'function.php';

// Ambil data barang masuk untuk dropdown, kecuali barang yang sudah ditandai sebagai keluar
$barangMasuk = $conn->query("
    SELECT Nama, jenis, harga 
    FROM masuk 
    WHERE Nama NOT IN (SELECT Nama FROM keluar)
");

// Periksa apakah formulir disubmit
if (isset($_POST["submit"])) {
    // Ambil data dari setiap elemen dalam formulir
    $Nama = $_POST["Nama"];
    $jenis = $_POST["jenis"];
    $Tanggal_keluar = $_POST["Tanggal_keluar"];
    $jumlah = $_POST["jumlah"];
    $harga = $_POST["harga"];

    // Siapkan pernyataan insert
    $stmt = $conn->prepare("INSERT INTO keluar (Nama, jenis, Tanggal_keluar, jumlah, harga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $Nama, $jenis, $Tanggal_keluar, $jumlah, $harga);

    // Eksekusi kueri dan periksa kesalahan
    if ($stmt->execute()) {
        // Data berhasil dimasukkan
        echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;'>Data berhasil dimasukkan!</div>";
    } else {
        // Terjadi kesalahan saat memasukkan data
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Kesalahan: " . $stmt->error . "</div>";
    }

    // Tutup pernyataan
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data</title>
    <link rel="stylesheet" href="add_data.css">
    <script>
        function updateForm() {
            const barangData = JSON.parse(document.getElementById('Nama').selectedOptions[0].dataset.details);
            document.getElementById('jenis').value = barangData.jenis;
            document.getElementById('harga').value = Math.ceil(barangData.harga * 1.1); // Harga 10% lebih mahal
        }
    </script>
    <style>
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Tambah Barang Keluar</h1>
        <form action="" method="post">
            <ul>
                <li>
                    <label for="Nama">Nama:</label>
                    <select name="Nama" id="Nama" onchange="updateForm()" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php while ($row = $barangMasuk->fetch_assoc()) : ?>
                            <option value="<?= $row['Nama'] ?>" data-details='{"jenis":"<?= $row['jenis'] ?>","harga":<?= $row['harga'] ?>}'>
                                <?= $row['Nama'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </li>
                <li>
                    <label for="jenis">Jenis:</label>
                    <input type="text" name="jenis" id="jenis" readonly required>
                </li>
                <li>
                    <label for="Tanggal_keluar">Tanggal keluar:</label>
                    <input type="date" name="Tanggal_keluar" id="Tanggal_keluar" required>
                </li>
                <li>
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="jumlah" id="jumlah" required>
                </li>
                <li>
                    <label for="harga">Harga:</label>
                    <input type="number" name="harga" id="harga" readonly required>
                </li>
                <li>
                    <button type="submit" name="submit">Tambah</button>
                </li>
            </ul>

            <div class="back-container">
                <a href="keluar.php" class="back-btn">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>