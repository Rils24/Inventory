<?php
// Connect to the database
require 'function.php';

// Check if the request contains the Nama parameter
if (isset($_GET['Nama'])) {
    $nama = $_GET['Nama'];

    // Query to get the price of the selected item from barang_masuk
    $query = "SELECT harga FROM barang_masuk WHERE Nama = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Return the price as JSON
        echo json_encode(['harga' => $row['harga']]);
    } else {
        // Return an empty response if no item is found
        echo json_encode(['harga' => null]);
    }

    // Close the statement
    $stmt->close();
} else {
    // Return an error response if Nama parameter is not provided
    echo json_encode(['error' => 'Nama parameter is required']);
}
?>
