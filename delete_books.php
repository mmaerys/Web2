<?php
include("connect.php");

if (isset($_POST['productIds']) && is_array($_POST['productIds'])) {
    $productIds = $_POST['productIds'];

    error_log("Received productIds: " . implode(', ', $productIds));

    mysqli_query($con, "SET foreign_key_checks = 0");

    $deleteQuery = "DELETE FROM items WHERE ItemID IN (" . implode(',', array_fill(0, count($productIds), '?')) . ")";
    $stmt = mysqli_prepare($con, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, str_repeat('i', count($productIds)), ...$productIds);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_param($stmt2, str_repeat('i', count($productIds)), ...$productIds);
        mysqli_stmt_execute($stmt2);


        if (mysqli_stmt_error($stmt)) {
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        error_log("Executed query: " . $deleteQuery . " with ItemID: " . implode(', ', $productIds));

        mysqli_stmt_close($stmt);

        mysqli_query($con, "SET foreign_key_checks = 1");

        echo "Selected Category deleted successfully";
    } else {
        echo "Error in preparing the delete statement";
    }
} else {
    echo "Invalid request";
}

?>