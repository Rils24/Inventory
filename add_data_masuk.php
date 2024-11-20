<?php
// Connect to the database
require 'function.php';



//untuk barang masuk
// Check if the submit button has been pressed
if (isset($_POST["submit"])) {
    // Retrieve data from each element in the form
    $Nama = $_POST["Nama"];
    $jenis = $_POST["jenis"];
    $Tanggal_masuk = $_POST["Tanggal_masuk"];
    $jumlah = $_POST["jumlah"];
    $harga = $_POST["harga"];

    // Prepare an insert statement
    $stmt = $conn->prepare("INSERT INTO masuk (Nama, jenis, Tanggal_masuk, jumlah, harga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $Nama, $jenis, $Tanggal_masuk, $jumlah, $harga);

    // Execute the query and check for errors
    if ($stmt->execute()) {
        // Display success message
        echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;'>Data successfully inserted!</div>";
    } else {
        // Display error message
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Error: " . $stmt->error . "</div>";
    }

    // Close the statement
    $stmt->close();
}
// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Data</title>
    <link rel="stylesheet" href="add_data.css">
</head>
<body>
    <div class="form-container">
        <h1>Tambah Barang Masuk</h1>
        <form action="" method="post">
            <ul>
                <li>
                    <label for="Nama">Nama:</label>
                    <input type="text" name="Nama" id="Nama" required>
                </li>
                <li>
                    <label for="jenis">Jenis:</label>
                    <input type="text" name="jenis" id="jenis" required>
                </li>
                <li>
                    <label for="Tanggal_masuk">Tanggal Masuk:</label>
                    <input type="date" name="Tanggal_masuk" id="Tanggal_masuk" required>
                </li>
                <li>
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="jumlah" id="jumlah" required>
                </li>
                <li>
                    <label for="harga">Harga:</label>
                    <input type="number" name="harga" id="harga" required>
                </li>
                <li>
                    <button type="submit" name="submit">Tambah</button>
                </li>
            </ul>

            <div class="back-container">
                <a href="masuk.php" class="back-btn">Back</a>
            </div>
        </form>
    </div>
</body>
</html>



<!-- <script>
        document.getElementById('add-row').addEventListener('click', () => {
            const nama = document.getElementById('nama').value;
            const jenis = document.getElementById('jenis').value;
            const tanggalMasuk = document.getElementById('tanggal-masuk').value;
            const jumlah = document.getElementById('jumlah').value;
            const harga = document.getElementById('harga').value;

            if (nama && jenis && tanggalMasuk && jumlah && harga) {
                const newData = {
                    nama,
                    jenis,
                    tanggalMasuk,
                    jumlah,
                    harga
                };

                let data = JSON.parse(localStorage.getItem('tableData')) || [];
                data.push(newData);
                localStorage.setItem('tableData', JSON.stringify(data));

                alert('Data added successfully!');
                document.getElementById('nama').value = '';
                document.getElementById('jenis').value = '';
                document.getElementById('tanggal-masuk').value = '';
                document.getElementById('jumlah').value = '';
                document.getElementById('harga').value = '';
            } else {
                alert('Please fill out all fields.');
            }
        });
    </script> -->