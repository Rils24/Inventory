<?php
// Connect to the database
require 'function.php';

// Check if the form is submitted
if (isset($_POST["submit"])) {
    // Retrieve data from each element in the form
    $Nama = $_POST["Nama"];
    $Tanggal_keluar = $_POST["Tanggal_keluar"];
    $jumlah = $_POST["jumlah"];
    $harga_keluar = $_POST["harga"];

    // Get item details from barang_masuk
    $query = "SELECT jenis FROM barang_masuk WHERE Nama = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $Nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $jenis = $row['jenis'];

        // Prepare an insert statement for barang keluar
        $insertQuery = "INSERT INTO keluar (Nama, jenis, Tanggal_keluar, jumlah, harga) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssii", $Nama, $jenis, $Tanggal_keluar, $jumlah, $harga_keluar);

        // Execute the query and check for errors
        if ($insertStmt->execute()) {
            // Data inserted successfully
            echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;'>Data successfully inserted!</div>";
        } else {
            // Error occurred while inserting data
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Error: " . $insertStmt->error . "</div>";
        }

        // Close the statement
        $insertStmt->close();
    } else {
        // No matching item found in barang_masuk
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Error: Item not found in barang masuk!</div>";
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Outgoing Items</title>
    <link rel="stylesheet" href="add_data.css">
    <script>
        function updateHarga() {
            const namaSelect = document.getElementById("Nama");
            const hargaInput = document.getElementById("harga");

            // Fetch the price from barang_masuk using AJAX
            const nama = namaSelect.value;
            fetch(`get_harga.php?Nama=${nama}`)
                .then(response => response.json())
                .then(data => {
                    if (data.harga) {
                        // Calculate 10% higher price
                        hargaInput.value = (data.harga * 1.1).toFixed(2);
                    } else {
                        hargaInput.value = "";
                    }
                })
                .catch(error => console.error("Error fetching harga:", error));
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h1>Tambah Barang Keluar</h1>
        <form action="" method="post">
            <ul>
                <li>
                    <label for="Nama">Nama:</label>
                    <select name="Nama" id="Nama" onchange="updateHarga()" required>
                        <option value="">Pilih Barang</option>
                        <?php
                        // Fetch item names from barang_masuk
                        $itemQuery = "SELECT Nama FROM barang_masuk";
                        $itemResult = $conn->query($itemQuery);
                        while ($item = $itemResult->fetch_assoc()) {
                            echo "<option value='" . $item['Nama'] . "'>" . $item['Nama'] . "</option>";
                        }
                        ?>
                    </select>
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
