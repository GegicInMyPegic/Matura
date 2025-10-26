<?php
    session_start();

    if (!empty($_POST)) {
        $name = $_POST["name"];
        $address = $_POST["address"];
        $opens = $_POST["opens"];
        $closes = $_POST["closes"];
        $limit = $_POST["limit"];

        $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $sql = "INSERT INTO igrisce (ime, naslov, odpre, zapre, st_teden, uporabnik_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }

        $stmt->bind_param("ssssii", $name, $address, $opens, $closes, $limit, $_SESSION['id']);

        if ($stmt->execute()) {
            $last_id = $conn->insert_id;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                    $newFileName = $last_id . "." . $fileExtension;
                    $destinationPath = "slike/" . $newFileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $destinationPath)) {
                        echo "File uploaded successfully.";
                    } else {
                        echo "Failed to move the uploaded file.";
                    }
                } else {
                    echo "Invalid file type.";
                }
            } else {
                echo "No file uploaded or there was an upload error.";
            }
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();

        header('Location: index.php');
        exit;
    }

    
    if (isset($_POST['logout'])) {
        $_SESSION = array();

        session_unset();
        session_destroy();
        header("Location: index.php"); 
        exit();
    }

    $timeSlots = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00","09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a court</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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

    <div class="row">
        <div class="col-3 col-sm-3"></div>

        <div class="col-7 col-md-6">

            <div class="container mt-5">
                <h2>Create your court:</h2>
                <form action="" method="post" onsubmit="return validateTimes()" enctype="multipart/form-data">
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                        <input type="text" class="form-control" name="name" placeholder="Name of the court">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Address</span>
                        <input type="text" class="form-control" name="address" placeholder="Address, postcode city">
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" id="imageUpload" name="image" accept="image/*">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="timeFrom" class="form-label">Opens:</label>
                                <select class="form-select" id='opens' name="opens">
                                <?php 
                                    foreach ($timeSlots as $slot) {
                                        echo "<option  value=\"$slot\">$slot</option>";}
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="timeTo" class="form-label">Closes:</label>
                                <select class="form-select" id='closes' name="closes">
                                    <?php 
                                        foreach ($timeSlots as $slot) {
                                            echo "<option  value=\"$slot\">$slot</option>";}
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="timeTo" class="form-label">Limit of reservations per week</label>
                                <select class="form-select" name="limit">
                                    <?php 
                                        for ($i = 0; $i < 40; $i++)
                                            echo "<option value=\"$i\">$i</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="col-2 col-sm-3"></div>
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

    <script>
        function validateTimes() {
            var opens = document.getElementById('opens').value;
            var closes = document.getElementById('closes').value;
            if (opens >= closes) {
                alert('Close time must be later than open time.');
                return false;
            }
            return true;
        }
    </script>


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