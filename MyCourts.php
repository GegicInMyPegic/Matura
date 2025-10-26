<?php
    session_start();
    $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
    

    if (isset($_POST['delete']) && isset($_POST['court_id'])) {
        $courtid = $_POST['court_id'];  
        $delete_sql = "DELETE FROM rezervacija WHERE rezervacija.igrisce_id = $courtid";
        $conn->query($delete_sql);


        $delete_sql = "DELETE FROM igrisce WHERE id = $courtid AND uporabnik_id = {$_SESSION['id']}";
        $conn->query($delete_sql);
    }

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: sign_in.php");
        exit();
    }
    $idu = $_SESSION["id"];

    $sql = "SELECT ime , id 
            FROM igrisce
            where uporabnik_id = $idu ";

    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>My Courts</title>
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
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid d-flex justify-content-evenly">
            <div>
                <a class="navbar-brand" href="Index.php">Home</a>
            </div>
            <form class="d-flex" method="post">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            My Account
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="MyReservations.php">My Reservations</a></li>
                            <li><a class="dropdown-item" href="MyCourts.php">My Courts</a></li>
                            <li><button type="submit" class="dropdown-item text-danger logout-link" name="logout">Log out</button></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="sign_in.php" class="btn btn-primary">Sign in</a>
                <?php endif; ?>
            </form>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>My Courts</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Court Name</th>
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ime']); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="court_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                <?php endwhile; ?>
                <tr>
                    <td colspan="2">
                        <a href="create.php" class="btn btn-outline-secondary" style="width:100%; ">+</a>
                    </td>
                </tr>
            </tbody>
        </table>
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

</body>
</html>
