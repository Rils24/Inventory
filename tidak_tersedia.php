<?php
require 'function.php';
// Ambil semua data barang tidak tersedia
$queryDataTidakTersedia = "SELECT 
                             Nama, 
                             jenis, 
                             SUM(jumlah) AS jumlah_masuk,
                             COALESCE((SELECT SUM(jumlah) FROM keluar WHERE keluar.Nama = masuk.Nama AND keluar.jenis = masuk.jenis), 0) AS jumlah_keluar
                           FROM masuk
                           GROUP BY Nama, jenis
                           HAVING (jumlah_masuk - jumlah_keluar) = 0";
$tidakTersedia = query($queryDataTidakTersedia);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Tidak Tersedia</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Gudang Jamilla</h3>
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
        </div>
        <ul class="sidebar-menu">
            <li class="logo"><a href="Home.php"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="logo"><a href="masuk.php"><i class="fas fa-arrow-down"></i> Barang Masuk</a></li>
            <li class="logo"><a href="keluar.php"><i class="fas fa-arrow-up"></i> Barang Keluar</a></li>
            <li class="logo"><a href="tersedia.php"><i class="fas fa-warehouse"></i> Barang Tersedia</a></li>
            <li class="logo"><a href="tidak_tersedia.php"><i class="fas fa-box-open"></i> Barang Tidak Tersedia</a></li>
            <li class="logo"><a class="logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="header">
            <button class="open-btn" onclick="toggleSidebar()">&#9776;</button>
            <h1>Barang Tidak Tersedia</h1>
        </div>
        <div class="filter-search-container">
            <!-- Filter dan Pencarian -->
            <div class="search-container">
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari...">
            </div>
            <div class="filter-container">
                <form action="cetak_laporan_tidak_tersedia.php" method="get" target="_blank">
                    <button type="submit">Cetak Laporan</button>
                </form>
            </div>
        </div>

        <!-- Tabel Barang Tidak Tersedia -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Jumlah Masuk</th>
                            <th>Jumlah Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tidakTersedia as $barang): ?>
                            <tr>
                                <td><?= htmlspecialchars($barang['Nama']); ?></td>
                                <td><?= htmlspecialchars($barang['jenis']); ?></td>
                                <td><?= htmlspecialchars($barang['jumlah_masuk']); ?></td>
                                <td><?= htmlspecialchars($barang['jumlah_keluar']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk filter pencarian -->
    <script src="srcpit.js"></script>
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toLowerCase();
            table = document.getElementById("dataTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
</body>

</html>