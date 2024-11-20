<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "gudang");

// Mengecek apakah koneksi ke database berhasil.
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk menghapus data masuk
function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM masuk WHERE id = $id");
    return mysqli_affected_rows($conn);
}

// Fungsi untuk menghapus data keluar
function hapus_keluar($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM keluar WHERE id = $id");
    return mysqli_affected_rows($conn);
}

// Fungsi untuk mendapatkan data per periode
function getDataPerPeriod($period) {
    global $conn;  // Menggunakan koneksi global
    $queryMasuk = "SELECT DATE_FORMAT(Tanggal_masuk, '$period') AS period, SUM(jumlah) AS total FROM masuk GROUP BY period";
    $queryKeluar = "SELECT DATE_FORMAT(Tanggal_keluar, '$period') AS period, SUM(jumlah) AS total FROM keluar GROUP BY period";
    
    $resultMasuk = mysqli_query($conn, $queryMasuk);
    $resultKeluar = mysqli_query($conn, $queryKeluar);
    
    $dataMasuk = [];
    $dataKeluar = [];
    
    while ($row = mysqli_fetch_assoc($resultMasuk)) {
        $dataMasuk[] = $row;
    }
    
    while ($row = mysqli_fetch_assoc($resultKeluar)) {
        $dataKeluar[] = $row;
    }
    
    return [
        'masuk' => $dataMasuk,
        'keluar' => $dataKeluar
    ];
}
?>
