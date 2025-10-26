<?php
    session_start();
    
    // Check if the logout post is set
    if (isset($_POST['logout'])) {
        error_log("Logout initiated");
        // Clear session
        $_SESSION = array();
        session_unset();
        session_destroy();

        // Redirect and stop script execution
        header("Location: index.php"); 
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - [Your Website Name]</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
                <!-- Dropdown for user options -->
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
    <div class="container my-5">
        <h1 class="mb-4 text-center header-color p-3">About Us</h1>
        <div class="row">
            <div class="col-md-12">
                <p>Welcome to my webstie, the premier online destination for all your reservation needs. Whether you're planning a dinner date, a family vacation, or a special event, our platform offers a seamless, user-friendly solution to book your next outing.</p>
                <p>Established in 20204, we have grown to become a trusted name in online reservations, connecting millions of users with a wide range of services and providers. Our mission is to simplify the booking process while providing reliable and memorable experiences.</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6 vision">
                <h3>Our Vision</h3>
                <p>Here, our vision is to revolutionize how people make reservations. We strive to offer the most extensive selection of services and venues, all at your fingertips.</p>
            </div>
            <div class="col-md-6 team">
                <h3>Our Team</h3>
                <p>Our team consists of dedicated professionals from across the globe, all focused on enhancing your booking experience. From technology experts to customer service representatives, each team member is committed to delivering excellence.</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 list-color">
                <h3>Why Choose Us?</h3>
                <ul>
                    <li>User-friendly interface – quick and easy reservation process.</li>
                    <li>Comprehensive selection of services and locations.</li>
                    <li>Secure and reliable booking with instant confirmation.</li>
                    <li>24/7 customer support to assist you whenever needed.</li>
                </ul>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
