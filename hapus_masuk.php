<?php
require 'function.php';

$id = $_GET["id"];

if (hapus($id) > 0) {
    // Data deleted successfully
    $message = "Data has been deleted successfully!";
    $alertClass = "success";
} else {
    // Failed to delete data
    $message = "Failed to delete data. Please try again.";
    $alertClass = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Data</title>
    <link rel="stylesheet" href="hapus.css">
</head>
<body>
    <div class="container">
        <div class="notification <?php echo $alertClass; ?>">
            <?php echo $message; ?>
        </div>
        <a href="masuk.php" class="btn">Back to Home</a>
    </div>
</body>
</html>
