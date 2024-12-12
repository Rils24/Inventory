<?php
// Connect to the database
require 'function.php';

// Fetch barang masuk data for the dropdown
$barangMasuk = $conn->query("
    SELECT Nama, jenis, harga, SUM(jumlah) AS total_jumlah 
    FROM masuk 
    GROUP BY Nama, jenis, harga
");

// Check if the form is submitted
if (isset($_POST["submit"])) {
    // Retrieve data from each element in the form
    $Nama = $_POST["Nama"];
    $jenis = $_POST["jenis"];
    $Tanggal_keluar = $_POST["Tanggal_keluar"];
    $jumlah = $_POST["jumlah"];
    $harga = $_POST["harga"];

    // Prepare an insert statement
    $stmt = $conn->prepare("INSERT INTO keluar (Nama, jenis, Tanggal_keluar, jumlah, harga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $Nama, $jenis, $Tanggal_keluar, $jumlah, $harga);

    // Execute the query and check for errors
    if ($stmt->execute()) {
        echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;'>Data successfully inserted!</div>";
    } else {
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Data</title>
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
                            <option value="<?= $row['Nama'] ?>" 
                                data-details='{"jenis":"<?= $row['jenis'] ?>","harga":<?= $row['harga'] ?>,"total_jumlah":<?= $row['total_jumlah'] ?>}'>
                                <?= $row['Nama'] ?> (Total: <?= $row['total_jumlah'] ?>)
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
                <a href="keluar.php" class="back-btn">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
