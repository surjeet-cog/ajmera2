
<?php

$conn = mysqli_connect('localhost','webtesting_ajmera', '$W4ud^1FXGY7', 'webtesting_ajmera');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_query($conn,"SET NAMES 'utf8'");


?>



