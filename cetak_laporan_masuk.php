<?php
require 'function.php';

// Mengambil nilai filter dari URL
$filterType = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
$filterValue = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';

$filterTanggal = '';

// Menangani pilihan filter berdasarkan jenisnya
switch ($filterType) {
    case 'tanggal':
        $filterTanggal = "WHERE Tanggal_masuk = '$filterValue'";
        break;
    case 'bulan':
        $filterTanggal = "WHERE DATE_FORMAT(Tanggal_masuk, '%Y-%m') = '$filterValue'";
        break;
    case 'tahun':
        $filterTanggal = "WHERE YEAR(Tanggal_masuk) = '$filterValue'";
        break;
    default:
        $filterTanggal = '';
        break;
}

// Ambil data masuk sesuai dengan filter
$queryDataMasuk = "SELECT * FROM masuk $filterTanggal";
$masuk = query($queryDataMasuk);

// Query untuk menghitung total transaksi berdasarkan filter
$queryTotalTransaksi = "SELECT SUM(harga * jumlah) AS total FROM masuk $filterTanggal";
$resultTotalTransaksi = query($queryTotalTransaksi);
$totalTransaksi = $resultTotalTransaksi[0]['total'] ? $resultTotalTransaksi[0]['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Barang Masuk</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .content {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            text-align: center;
            color: #333;
        }
        .header p {
            text-align: center;
            color: #666;
        }
        .table-responsive {
            margin-top: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #00509e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #e0f0ff;
        }
        tr:hover {
            background-color: #cfe2ff;
        }
        .total-transaksi {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
            color: #333;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        .print-button button {
            background-color: #00509e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-button button:hover {
            background-color: #003f7e;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }
            .content {
                box-shadow: none;
                padding: 0;
                margin: 0;
                border: none;
                width: 100%;
            }
            .print-button {
                display: none;
            }
            th {
                background-color: #003f7e !important;
                -webkit-print-color-adjust: exact;
                color: white !important;
            }
            tr:nth-child(even) {
                background-color: #e0f0ff !important;
                -webkit-print-color-adjust: exact;
            }
            tr:hover {
                background-color: #cfe2ff !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h1>Laporan Barang Masuk</h1>
            <?php if ($filterType && $filterValue): ?>
                <p>Filter: <?= ucfirst($filterType) ?> - <?= $filterValue ?></p>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Tanggal Masuk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($masuk as $row) : ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $row["Nama"]; ?></td>
                                <td><?= $row["jenis"]; ?></td>
                                <td><?= $row["Tanggal_masuk"]; ?></td>
                                <td><?= $row["jumlah"]; ?></td>
                                <td><?= 'Rp ' . number_format($row["harga"], 2, ',', '.'); ?></td>
                                <td><?= 'Rp ' . number_format($row["harga"] * $row["jumlah"], 2, ',', '.'); ?></td> <!-- Menampilkan total harga per item -->
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="total-transaksi">
                <h2>Total Transaksi: Rp <?= number_format($totalTransaksi, 2, ',', '.'); ?></h2>
            </div>
            <div class="print-button">
                <button onclick="window.print()">Cetak</button>
            </div>
        </div>
    </div>
</body>
</html>
