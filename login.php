<?php
// Koneksi ke database
require 'function.php';

session_start();

// Cek apakah form login telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Siapkan pernyataan SQL untuk memeriksa username dan password
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Cek apakah username ada di database
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $db_password)) {
            // Simpan informasi pengguna di sesi
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $db_username;

            // Arahkan pengguna ke halaman home.php
            header("location: home.php");
            exit;
        } else {
            // Password salah
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Password salah.</div>";
        }
    } else {
        // Username tidak ditemukan
        echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>Username tidak ditemukan.</div>";
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Barang Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <!-- <p>Belum punya akun? <a href="registrasi.php">Silahkan daftar</a></p> -->
    </div>
</body>
</html>
