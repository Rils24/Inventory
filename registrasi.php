<?php
// Koneksi ke database
require 'function.php';

// Proses registrasi pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        // Registrasi berhasil
        echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;'>Registrasi berhasil!</div>";
    } else {
        // Registrasi gagal
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Registrasi gagal: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Tutup koneksi
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Barang Registrasi</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Registrasi</h1>
        <form action="" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="password2">Konfirmasi Password</label>
            <input type="password" id="password2" name="password" required>


            <button type="submit">Registrasi</button>

        </form>
        <p>Sudah punya akun? <a href="login.php">Silahkan login</a></p>
    </div>
</body>
</html>