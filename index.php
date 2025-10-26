<?php
    session_start();
    
    if (isset($_POST['logout'])) {
        error_log("Logout initiated");
        $_SESSION = array();
        session_unset();
        session_destroy();

        header("Location: index.php"); 
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en" style="max-width: 100%; overflow-x: hidden; min-height: 100%; display: flex; flex-direction: column; height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
            width: 100%; 
        }
        footer a {
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline; 
        }
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        footer {
            width: 100%;
            line-height: 60px;
            position: relative;
            margin-top: auto; 
            background: #000;
            color: white;
        }
        .container-fluid {
            padding-right: 0;
            padding-left: 0;
        }
        html{
            max-width: 100%; 
            overflow-x: hidden; 
            min-height: 100%; 
            display: flex; 
            flex-direction: column; 
        }
        body{
            height: 100%;
            position: relative;
        }
    </style>
</head>
<body >

    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid d-flex justify-content-evenly">
            <a class="navbar-brand" href="Index.php">Home</a>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        My Account
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="MyReservations.php">My Reservations</a></li>
                        <li><a class="dropdown-item" href="MyCourts.php">My Courts</a></li>
                        <li><form method="post"><button type="submit" class="dropdown-item text-danger logout-link" name="logout">Log out</button></form></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="sign_in.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-3" id="search-container">
        <form class="d-flex justify-content-center">
            <input class="form-control" style="width: 30%;" type="search" placeholder="Search" aria-label="Search" name="search" id="searchInput">
            <button class="btn btn-primary ms-2" type="submit">Search</button>
        </form>
        <hr> 
    </div>


    <div id="content-wrap">
        <div class="row">
            <div class="col-2 col-sm-3"></div>
            <div class="col-8 col-md-6">
            <?php
                $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search_term = $conn->real_escape_string($_GET['search']);
                    $sql = "SELECT * FROM igrisce WHERE ime LIKE '%" . $search_term . "%' OR naslov LIKE '%" . $search_term . "%'";
                } else {
                    $sql = "SELECT * FROM igrisce";
                }
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo '<div class="container mt-3">';
                    echo '<div class="row">';
                    while ($igrisce = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-3">';
                        echo '<div class="card" style="width: 100%;">';

                        $extensions = ['jpg', 'jpeg', 'png'];
                        $imagePath = '';
                        foreach ($extensions as $ext) {
                            if (file_exists("slike/" . $igrisce["id"] . "." . $ext)) {
                                $imagePath = "slike/" . $igrisce["id"] . "." . $ext;
                                break;
                            }
                        }

                        echo '<img src="' . ($imagePath ?: 'slike/placeholder.png') . '" class="card-img-top" alt="Image of Court ' . $igrisce["ime"] . '">';

                        echo '<div class="px-2">';
                        echo '<div class="d-flex justify-content-center">' . $igrisce["ime"] . '</div>';
                        echo '<div class="d-flex justify-content-center">' . $igrisce["naslov"] . '</div>';
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<a href="court.php?id=' . $igrisce["id"] . '" class="btn btn-primary d-flex justify-content-center">Reserve</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo "0 results";
                }
                $conn->close();
            ?>

            </div>
            <div class="col-2 col-sm-3"></div>
        </div>
    </div>

    <footer class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-start">
                    © 2024 Gegic 4 Predmet
                </div>
                <div class="col-md-6 text-end">
                    <a href="about.php" class="text-white pe-2">About Us</a> |
                    <a href="mailto:gegic.gregor@gmail.com" class="text-white ps-2">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

    <form id="logout-form" method="post" onsubmit="return false;">
        <input type="hidden" name="logout">
    </form>

    <script>
        document.getElementById('logout-form').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                return false;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>
</html>
