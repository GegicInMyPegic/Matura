<?php
    $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>