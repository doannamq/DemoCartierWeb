<?php
// search_results.php
if (isset($_POST["search"])) {
    $search = $_POST["search"];
    // Connect to your MySQL database (replace with your actual credentials)
    include('server/connection.php');

    $sql = "SELECT product_name FROM products WHERE product_name LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>{$row['product_name']}</div>";
        }
    } else {
        echo "No results found";
    }

    $conn->close();
}
