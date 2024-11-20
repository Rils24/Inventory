<?php
// Koneksi ke database
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

// Ambil data untuk dashboard
$totalMasuk = query("SELECT SUM(jumlah) AS total FROM masuk")[0]['total'];
$totalKeluar = query("SELECT SUM(jumlah) AS total FROM keluar")[0]['total'];
$totalStok = $totalMasuk - $totalKeluar;

// Ambil data per hari
$queryPerHari = "SELECT DATE(Tanggal_masuk) AS tanggal, SUM(jumlah) AS total_masuk 
                 FROM masuk 
                 GROUP BY DATE(Tanggal_masuk)
                 ORDER BY DATE(Tanggal_masuk) ASC";
$dataPerHari = query($queryPerHari);

$queryKeluarPerHari = "SELECT DATE(Tanggal_keluar) AS tanggal, SUM(jumlah) AS total_keluar 
                       FROM keluar 
                       GROUP BY DATE(Tanggal_keluar)
                       ORDER BY DATE(Tanggal_keluar) ASC";
$dataKeluarPerHari = query($queryKeluarPerHari);

// Ambil data per minggu
$queryPerMinggu = "SELECT YEARWEEK(Tanggal_masuk) AS minggu, SUM(jumlah) AS total_masuk 
                   FROM masuk 
                   GROUP BY YEARWEEK(Tanggal_masuk)
                   ORDER BY YEARWEEK(Tanggal_masuk) ASC";
$dataPerMinggu = query($queryPerMinggu);

$queryKeluarPerMinggu = "SELECT YEARWEEK(Tanggal_keluar) AS minggu, SUM(jumlah) AS total_keluar 
                         FROM keluar 
                         GROUP BY YEARWEEK(Tanggal_keluar)
                         ORDER BY YEARWEEK(Tanggal_keluar) ASC";
$dataKeluarPerMinggu = query($queryKeluarPerMinggu);

// Ambil data per bulan
$queryPerBulan = "SELECT DATE_FORMAT(Tanggal_masuk, '%Y-%m') AS bulan, SUM(jumlah) AS total_masuk 
                  FROM masuk 
                  GROUP BY DATE_FORMAT(Tanggal_masuk, '%Y-%m')
                  ORDER BY DATE_FORMAT(Tanggal_masuk, '%Y-%m') ASC";
$dataPerBulan = query($queryPerBulan);

$queryKeluarPerBulan = "SELECT DATE_FORMAT(Tanggal_keluar, '%Y-%m') AS bulan, SUM(jumlah) AS total_keluar 
                        FROM keluar 
                        GROUP BY DATE_FORMAT(Tanggal_keluar, '%Y-%m')
                        ORDER BY DATE_FORMAT(Tanggal_keluar, '%Y-%m') ASC";
$dataKeluarPerBulan = query($queryKeluarPerBulan);

// Ambil data per tahun
$queryPerTahun = "SELECT YEAR(Tanggal_masuk) AS tahun, SUM(jumlah) AS total_masuk 
                  FROM masuk 
                  GROUP BY YEAR(Tanggal_masuk)
                  ORDER BY YEAR(Tanggal_masuk) ASC";
$dataPerTahun = query($queryPerTahun);

$queryKeluarPerTahun = "SELECT YEAR(Tanggal_keluar) AS tahun, SUM(jumlah) AS total_keluar 
                        FROM keluar 
                        GROUP BY YEAR(Tanggal_keluar)
                        ORDER BY YEAR(Tanggal_keluar) ASC";
$dataKeluarPerTahun = query($queryKeluarPerTahun);


// Ambil total nilai barang masuk
$totalNilaiMasuk = query("SELECT SUM(jumlah * harga) AS total FROM masuk")[0]['total'];

// Ambil total nilai barang keluar
$totalNilaiKeluar = query("SELECT SUM(jumlah * harga) AS total FROM keluar")[0]['total'];

// Hitung total uang yang dimiliki
$totalUang = $totalNilaiKeluar - $totalNilaiMasuk;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Barang</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Gudang Jamilla</h3>
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
        </div>
        <ul class="sidebar-menu">
        <li class="logo"><a href="#"><i class="fa-solid fa-house"></i> Home</a></li>
<li class="logo"><a href="masuk.php"><i class="fas fa-arrow-down"></i> Barang Masuk</a></li>
<li class="logo"><a href="keluar.php"><i class="fas fa-arrow-up"></i> Barang Keluar</a></li>
<li class="logo"><a href="tersedia.php"><i class="fas fa-warehouse"></i> Barang Tersedia</a></li>
<li class="logo"><a class="logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>

        </ul>
    </div>
    <div class="content">
        <header class="header">
            <button class="open-btn" onclick="toggleSidebar()">&#9776;</button>
            <h1>Dashboard</h1>
            <div class="user-info">
                <span>Welcome, <strong><?php echo $username; ?></strong></span>
                <!-- Anda bisa menambahkan foto profil atau informasi tambahan di sini -->
            </div>
        </header>
        <main>
            <section class="dashboard">
                <div class="cards">
                    <div class="card">
                        <h3>Total Stok</h3>
                        <p><?php echo $totalStok; ?> Items</p>
                    </div>
                    <div class="card">
                        <h3>Barang Masuk</h3>
                        <p><?php echo $totalMasuk; ?> Items</p>
                    </div>
                    <div class="card">
                        <h3>Barang Keluar</h3>
                        <p><?php echo $totalKeluar; ?> Items</p>
                    </div>
                     <div class="card">
                        <h3>Total Uang</h3>
                        <p>Rp <?php echo number_format($totalUang, 2, ',', '.'); ?></p>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="myChartPerHari"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="myChartPerMinggu"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="myChartPerBulan"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="myChartPerTahun"></canvas>
                </div>
            </section>
        </main>
    </div>
<script>
   // Data untuk grafik per hari
   var ctxPerHari = document.getElementById('myChartPerHari').getContext('2d');
var myChartPerHari = new Chart(ctxPerHari, {
    type: 'bar', // Mengubah tipe grafik menjadi batang (bar chart)
    data: {
        labels: [<?php foreach ($dataPerHari as $data) { echo "'" . $data['tanggal'] . "', "; } ?>],
        datasets: [{
            label: 'Barang Masuk Per Hari',
            data: [<?php foreach ($dataPerHari as $data) { echo $data['total_masuk'] . ", "; } ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.8)', // Warna biru untuk barang masuk
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }, {
            label: 'Barang Keluar Per Hari',
            data: [<?php foreach ($dataKeluarPerHari as $data) { echo $data['total_keluar'] . ", "; } ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.8)', // Warna merah untuk barang keluar
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2
        }]
    },
    options: {
        maintainAspectRatio: false, // Biarkan grafik tidak mempertahankan rasio aspeknya
        responsive: true, // Membuat grafik responsif
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)' // Warna grid
                },
                ticks: {
                    font: {
                        size: 14, // Ukuran font
                        family: 'Arial' // Jenis font
                    },
                    color: 'rgba(0, 0, 0, 0.7)' // Warna teks sumbu y
                },
                title: {
                    display: true,
                    text: 'Jumlah Barang',
                    font: {
                        size: 16, // Ukuran font judul
                        weight: 'bold' // Tebal font judul
                    },
                    color: 'rgba(0, 0, 0, 0.7)' // Warna teks judul
                }
            },
            x: {
                grid: {
                    display: false // Sembunyikan grid di sumbu x
                },
                ticks: {
                    font: {
                        size: 14, // Ukuran font
                        family: 'Arial' // Jenis font
                    },
                    color: 'rgba(0, 0, 0, 0.7)' // Warna teks sumbu x
                },
                title: {
                    display: true,
                    text: 'Tanggal',
                    font: {
                        size: 16, // Ukuran font judul
                        weight: 'bold' // Tebal font judul
                    },
                    color: 'rgba(0, 0, 0, 0.7)' // Warna teks judul
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top', // Menempatkan legenda di atas grafik
                labels: {
                    font: {
                        size: 14, // Ukuran font legenda
                        family: 'Arial' // Jenis font legenda
                    },
                    color: 'rgba(0, 0, 0, 0.7)' // Warna teks legenda
                }
            }
        },
        animation: {
            duration: 1500, // Durasi animasi
            easing: 'easeInOutQuart' // Jenis animasi
        }
    }
});

   // Data untuk grafik per minggu
var ctxPerMinggu = document.getElementById('myChartPerMinggu').getContext('2d');
var myChartPerMinggu = new Chart(ctxPerMinggu, {
    type: 'bar', // Mengubah tipe grafik menjadi batang (bar chart)
    data: {
        labels: [<?php foreach ($dataPerMinggu as $data) { echo "'" . $data['minggu'] . "', "; } ?>],
        datasets: [{
            label: 'Barang Masuk Per Minggu',
            data: [<?php foreach ($dataPerMinggu as $data) { echo $data['total_masuk'] . ", "; } ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }, {
            label: 'Barang Keluar Per Minggu',
            data: [<?php foreach ($dataKeluarPerMinggu as $data) { echo $data['total_keluar'] . ", "; } ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2
        }]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                },
                title: {
                    display: true,
                    text: 'Jumlah Barang',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                },
                title: {
                    display: true,
                    text: 'Minggu',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});

// Data untuk grafik per bulan
var ctxPerBulan = document.getElementById('myChartPerBulan').getContext('2d');
var myChartPerBulan = new Chart(ctxPerBulan, {
    type: 'bar', // Mengubah tipe grafik menjadi batang (bar chart)
    data: {
        labels: [<?php foreach ($dataPerBulan as $data) { echo "'" . $data['bulan'] . "', "; } ?>],
        datasets: [{
            label: 'Barang Masuk Per Bulan',
            data: [<?php foreach ($dataPerBulan as $data) { echo $data['total_masuk'] . ", "; } ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }, {
            label: 'Barang Keluar Per Bulan',
            data: [<?php foreach ($dataKeluarPerBulan as $data) { echo $data['total_keluar'] . ", "; } ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2
        }]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                },
                title: {
                    display: true,
                    text: 'Jumlah Barang',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                },
                title: {
                    display: true,
                    text: 'Bulan',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});


// Data untuk grafik per tahun
var ctxPerTahun = document.getElementById('myChartPerTahun').getContext('2d');
var myChartPerTahun = new Chart(ctxPerTahun, {
    type: 'bar', // Mengubah tipe grafik menjadi batang (bar chart)
    data: {
        labels: [<?php foreach ($dataPerTahun as $data) { echo "'" . $data['tahun'] . "', "; } ?>],
        datasets: [{
            label: 'Barang Masuk Per Tahun',
            data: [<?php foreach ($dataPerTahun as $data) { echo $data['total_masuk'] . ", "; } ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }, {
            label: 'Barang Keluar Per Tahun',
            data: [<?php foreach ($dataKeluarPerTahun as $data) { echo $data['total_keluar'] . ", "; } ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2
        }]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                },
                title: {
                    display: true,
                    text: 'Jumlah Barang',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                },
                title: {
                    display: true,
                    text: 'Tahun',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'rgba(0, 0, 0, 0.7)'
                }
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});

</script>


<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="srcpit.js"></script>
</body>

</html>