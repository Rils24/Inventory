<?php
require 'function.php';

// Mulai session di setiap halaman yang memerlukan autentikasi
session_start();

// Periksa apakah session username telah diset
if (!isset($_SESSION['username'])) {
    // Jika tidak ada session, redirect pengguna ke halaman login
    header("Location: login.php");
    exit();
}
// Sekarang Anda dapat menggunakan $_SESSION['username'] atau informasi session lainnya di sini
$username = $_SESSION['username'];
// Inisialisasi filter
$filterTanggal = '';

// Memeriksa jika form filter telah dikirim
if (isset($_POST['filter'])) {
    $filterType = $_POST['filter_type'];

    // Menangani pilihan filter berdasarkan jenisnya
    switch ($filterType) {
        case 'tanggal':
            if (isset($_POST['tanggal']) && !empty($_POST['tanggal'])) {
                $tanggal = $_POST['tanggal'];
                $filterTanggal = "WHERE Tanggal_keluar = '$tanggal'";
            } else {
                $filterTanggal = ''; // Set filter kosong jika tidak ada tanggal yang dipilih
            }
            break;
        case 'bulan':
            if (isset($_POST['bulan']) && !empty($_POST['bulan'])) {
                $bulan = $_POST['bulan'];
                $filterTanggal = "WHERE DATE_FORMAT(Tanggal_keluar, '%Y-%m') = '$bulan'";
            } else {
                $filterTanggal = ''; // Set filter kosong jika tidak ada bulan yang dipilih
            }
            break;
        case 'tahun':
            if (isset($_POST['tahun']) && !empty($_POST['tahun'])) {
                $tahun = $_POST['tahun'];
                $filterTanggal = "WHERE YEAR(Tanggal_keluar) = '$tahun'";
            } else {
                $filterTanggal = ''; // Set filter kosong jika tidak ada tahun yang dipilih
            }
            break;
        case 'semua':
            $filterTanggal = ''; // Menampilkan semua data tanpa filter
            break;
        default:
            $filterTanggal = ''; // Set filter kosong untuk default
            break;
    }
}

// Konfigurasi pagination
$jumlahDataPerHalaman = 5;
$queryJumlahData = "SELECT COUNT(*) AS jumlah FROM keluar $filterTanggal";
$resultJumlahData = query($queryJumlahData);
$jumlahData = $resultJumlahData[0]['jumlah'];
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["halaman"])) ? (int)$_GET["halaman"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Ambil data keluar sesuai dengan halaman yang dipilih dan filter
$queryDataKeluar = "SELECT * FROM keluar $filterTanggal ORDER BY Tanggal_keluar DESC LIMIT $awalData, $jumlahDataPerHalaman";

$keluar = query($queryDataKeluar);

// Query untuk menghitung total transaksi berdasarkan filter
$queryTotalTransaksi = "SELECT SUM(harga * jumlah) AS total FROM keluar $filterTanggal";
$resultTotalTransaksi = query($queryTotalTransaksi);
$totalTransaksi = $resultTotalTransaksi[0]['total'] ? $resultTotalTransaksi[0]['total'] : 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Barang Keluar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Gudang Jamilla </h3>
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
        </div>
        <ul class="sidebar-menu">
            <li class="logo"><a href="Home.php"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="logo"><a href="masuk.php"><i class="fas fa-arrow-down"></i> Barang Masuk</a></li>
            <li class="logo"><a href="#"><i class="fas fa-arrow-up"></i> Barang Keluar</a></li>
            <li class="logo"><a href="tersedia.php"><i class="fas fa-warehouse"></i> Barang Tersedia</a></li>
            <li class="logo"><a href="tidak_tersedia.php"><i class="fas fa-box-open"></i> Barang Tidak Tersedia</a></li>
            <li class="logo"><a class="logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="header">
            <button class="open-btn" onclick="toggleSidebar()">&#9776; </button>
            <h1>Barang Keluar </h1>
        </div>
        <div class="filter-search-container">
            <div class="search-container">
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari...">
            </div>
            <div class="filter-container">
                <form id="filterForm" action="" method="post">
                    <label for="filter_type">Pilih Tipe Filter:</label>
                    <select id="filter_type" name="filter_type" onchange="toggleFilterFields()">
                        <option value="tanggal">Tanggal Tertentu</option>
                        <option value="bulan">Bulan Tertentu</option>
                        <option value="tahun">Tahun Tertentu</option>
                        <option value="semua">Tampilkan Semua Data</option> <!-- Opsi tambahan untuk menampilkan semua data -->
                    </select>

                    <div id="tanggal_form">
                        <label for="tanggal">Pilih Tanggal:</label>
                        <input type="date" id="tanggal" name="tanggal">
                    </div>

                    <div id="bulan_form" style="display: none;">
                        <label for="bulan">Pilih Bulan:</label>
                        <input type="month" id="bulan" name="bulan">
                    </div>

                    <div id="tahun_form" style="display: none;">
                        <label for="tahun">Pilih Tahun:</label>
                        <input type="number" id="tahun" name="tahun" min="1900" max="2100">
                    </div>

                    <button type="submit" name="filter">Filter</button>
                    <button type="button" onclick="cetakLaporan()">Cetak Laporan</button>
                </form>
            </div>
        </div>

        <!-- Menampilkan total transaksi -->
        <div class="total-transaksi">
            <h2>Total Transaksi Barang Keluar: Rp <?= number_format($totalTransaksi, 2, ',', '.'); ?></h2>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>aksi</th>
                            <th>Nama</th>
                            <th>jenis</th>
                            <th>Tanggal keluar</th>
                            <th>jumlah</th>
                            <th>harga</th>
                            <th>Total Harga</th> <!-- Kolom baru untuk total harga -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($keluar as $row) : ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td>
                                    <!-- Form untuk tombol "Ubah" -->
                                    <form action="ubah_keluar.php" method="get">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <button type="submit" class="icon-link ubah">
                                            <i class="fa-solid fa-pen-to-square"></i> Ubah
                                        </button>
                                    </form>

                                    <!-- Form untuk tombol "Hapus" -->
                                    <form action="hapus_keluar.php" method="get" onsubmit="return confirm('Yakin Ingin Hapus?')">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <button type="submit" class="icon-link hapus">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                                <td><?= $row["Nama"]; ?></td>
                                <td><?= $row["jenis"]; ?></td>
                                <td><?= $row["Tanggal_keluar"]; ?></td>
                                <td><?= $row["jumlah"]; ?></td>
                                <td><?= 'Rp ' . number_format($row["harga"], 2, ',', '.'); ?></td>
                                <td><?= 'Rp ' . number_format($row["harga"] * $row["jumlah"], 2, ',', '.'); ?></td> <!-- Menampilkan total harga per item -->
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <!-- Tombol navigasi pagination -->
                <?php if ($halamanAktif > 1) : ?>
                    <a href="?halaman=<?= $halamanAktif - 1; ?>" class="pagination-link">&laquo; Sebelumnya</a>
                <?php endif; ?>

                <?php for ($halaman = 1; $halaman <= $jumlahHalaman; $halaman++) : ?>
                    <?php if ($halaman == $halamanAktif) : ?>
                        <a href="?halaman=<?= $halaman; ?>" class="pagination-link active"><?= $halaman; ?></a>
                    <?php else : ?>
                        <a href="?halaman=<?= $halaman; ?>" class="pagination-link"><?= $halaman; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($halamanAktif < $jumlahHalaman) : ?>
                    <a href="?halaman=<?= $halamanAktif + 1; ?>" class="pagination-link">Selanjutnya &raquo;</a>
                <?php endif; ?>
            </div>
            <div>
                <a href="add_data_keluar.php" class="add-btn">Tambah</a>
            </div>
        </div>  
    </div>

    <script src="srcpit.js"></script>
    <script>
        function toggleFilterFields() {
            const filterType = document.getElementById('filter_type').value;
            document.getElementById('tanggal_form').style.display = (filterType === 'tanggal') ? 'block' : 'none';
            document.getElementById('bulan_form').style.display = (filterType === 'bulan') ? 'block' : 'none';
            document.getElementById('tahun_form').style.display = (filterType === 'tahun') ? 'block' : 'none';
        }

        function cetakLaporan() {
            const filterForm = document.getElementById('filterForm');
            const filterType = document.getElementById('filter_type').value;
            let filterValue = '';

            switch (filterType) {
                case 'tanggal':
                    filterValue = document.getElementById('tanggal').value;
                    break;
                case 'bulan':
                    filterValue = document.getElementById('bulan').value;
                    break;
                case 'tahun':
                    filterValue = document.getElementById('tahun').value;
                    break;
            }

            if (filterValue || filterType === 'semua') {
                if (filterType === 'semua') {
                    window.open(`cetak_laporan_keluar.php?filter_type=${filterType}`, '_blank');
                } else {
                    window.open(`cetak_laporan_keluar.php?filter_type=${filterType}&filter_value=${filterValue}`, '_blank');
                }
            } else {
                alert('Harap pilih nilai filter terlebih dahulu.');
            }
        }

       
    </script>
</body>
</html>
