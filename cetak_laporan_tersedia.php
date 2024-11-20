<?php
require 'function.php';

// Ambil semua data barang tersedia
$queryDataTersedia = "SELECT 
                        Nama, 
                        jenis, 
                        SUM(jumlah) AS jumlah_masuk,
                        (SELECT SUM(jumlah) FROM keluar WHERE keluar.Nama = masuk.Nama AND keluar.jenis = masuk.jenis) AS jumlah_keluar,
                        (SELECT AVG(harga) FROM keluar WHERE keluar.Nama = masuk.Nama AND keluar.jenis = masuk.jenis) AS harga_satuan_keluar
                      FROM masuk
                      GROUP BY Nama, jenis";
$tersedia = query($queryDataTersedia);

// Hitung jumlah tersedia
foreach ($tersedia as &$barang) {
    $barang['jumlah_keluar'] = $barang['jumlah_keluar'] ? $barang['jumlah_keluar'] : 0;
    $barang['jumlah_tersedia'] = $barang['jumlah_masuk'] - $barang['jumlah_keluar'];
}
unset($barang);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Barang Tersedia</title>
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
            <h1>Laporan Barang Tersedia</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Jumlah Masuk</th>
                            <th>Jumlah Keluar</th>
                            <th>Jumlah Tersedia</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tersedia as $barang) : ?>
                            <tr>
                                <td><?= htmlspecialchars($barang['Nama']); ?></td>
                                <td><?= htmlspecialchars($barang['jenis']); ?></td>
                                <td><?= htmlspecialchars($barang['jumlah_masuk']); ?></td>
                                <td><?= htmlspecialchars($barang['jumlah_keluar']); ?></td>
                                <td><?= htmlspecialchars($barang['jumlah_tersedia']); ?></td>
                               
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="print-button">
                <button onclick="window.print()">Cetak</button>
            </div>
        </div>
    </div>
</body>
</html>
