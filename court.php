<?php
    session_start();

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.php"); 
        exit();
    }


    $ide = isset($_GET["id"]) ? $_GET["id"] : 1;  // Default to 1 if not provided
    $uid = isset($_SESSION["id"]) ? $_SESSION["id"] : -1;  // Use -1 or another invalid ID when not logged in

  
    $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM igrisce WHERE id = " . $ide;
    $result = $conn->query($sql);
    $igrisce = $result->fetch_assoc();
    $perweek = $igrisce["st_teden"] ?? 0;  // Default to 0 if not found

    $result->free();
    $cweek = true;

    if ($uid != -1) {
        // Check for four or more entries in a week only if user is logged in
        $sqlWeekly = "SELECT COUNT(*) AS count
            FROM rezervacija
            WHERE uporabnik_id = $uid AND igrisce_id = $ide 
            AND dan >= CURDATE() AND dan < DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            HAVING count >= $perweek";
            
        $result = $conn->query($sqlWeekly);
        if ($result->num_rows > 0) {
            $cweek = false;
        }
    }

    $conn->close();

    $dates = []; 
    $today = new DateTime(); 

    for ($i = 0; $i < 7; $i++) {
        if ($i > 0) {
            $today->add(new DateInterval('P1D'));
        }
        $dates[] = $today->format('Y-m-d');
    }
    $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch court data
    $sql = "SELECT * FROM igrisce WHERE id = $ide";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $igrisce = $result->fetch_assoc();
        // Check for multiple image file extensions
        $extensions = ['jpg', 'jpeg', 'png'];
        $imagePath = '';
        foreach ($extensions as $ext) {
            if (file_exists("slike/" . $ide . "." . $ext)) {
                $imagePath = "slike/" . $ide . "." . $ext;
                break;
            }
        }
    }

    $conn->close();
?>


<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .taken{
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            background-color: green;
        }
        .time-slot {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            background-color: #f8f9fa;
        }
        .time-slot:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        .table th, .table td {
            width: calc(100% / 8); 
            min-width: 120px; 
        }
        .table-responsive {
            overflow-x: auto;
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

    <br>

    <div class="container ">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4 col-sm-12">
                    <img src="<?= $imagePath ?: 'slike/placeholder.png' ?>" class="img-fluid" alt="Responsive image">
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="card-body">
                        <?php if (isset($igrisce)): ?>
                            <h2><?= $igrisce["ime"] ?></h2>
                            <br>
                            <h5><?= $igrisce["naslov"] ?></h5>
                            <br>
                            <p><strong>Number of reservations per week: </strong><?= $igrisce["st_teden"] ?></p>
                        <?php else: ?>
                            <p>Court information not available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <?php
                            $daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                            $todayIndex = date('w');
                            for ($i = 0; $i < 7; $i++) {
                                echo '<th>' . $daysOfWeek[($todayIndex + $i) % 7] . '</th>';
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $timeSlots = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00","09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"];
                        $sql = "SELECT odpre, zapre FROM igrisce WHERE id = $ide";
                        $result = $conn->query($sql);
                        $igrisce = $result->fetch_assoc();

                        for ($i = 0; $i < 24; $i++){
                            if($igrisce["odpre"] == $timeSlots[$i]){
                                $odpre = $i;
                            }
                            if($igrisce["zapre"] == $timeSlots[$i]){
                                $zapre = $i;
                            }
                        }



                        $sql = "SELECT dan, cas FROM rezervacija, igrisce WHERE rezervacija.igrisce_id = $ide ";
                        $result = $conn->query($sql);
                        $reservations = [];
                        while ($rezervacija = $result->fetch_assoc()) {
                            if (!isset($reservations[$rezervacija['dan']])) {
                                $reservations[$rezervacija['dan']] = [];  
                            }
                            $reservations[$rezervacija['dan']][$rezervacija['cas']] = true;  
                        }

                        for ($i = $odpre; $i < $zapre; $i++) {
                            echo '<tr><td>' . htmlspecialchars($timeSlots[$i]) . '</td>';  
                            for ($j = 0; $j < 7; $j++) {
                                
                                if (isset($reservations[$dates[$j]][$timeSlots[$i]])) {
                                    echo '<td class="taken bg-success">Reserved</td>';
                                } else {
                                    echo '<td class="time-slot" onclick="popup(\'' . htmlspecialchars($timeSlots[$i]) . '\', \'' . htmlspecialchars($dates[$j]) . '\', \'' . htmlspecialchars($ide) . '\')"></td>';
                                }
                            }
                            echo '</tr>';
                        }
                        $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="popup1" tabindex="-1" aria-labelledby="popup1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popup1">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form class="d-flex" action="reserve.php" method="post">
                        <input type="hidden" name="id" id="hiddenId">
                        <input type="hidden" name="time" id="hiddenTime">
                        <input type="hidden" name="day" id="hiddenDay">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                            <?php if ($cweek == false): ?>
                                <button type="submit" name="reserve" class="btn btn-secondary" disabled>Too many reservations</button>
                            <?php else: ?>
                                <button type="submit" name="reserve" class="btn btn-primary">Yes</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="sign_in.php" class="btn btn-primary">Sign in</a>
                        <?php endif; ?>

                    </form>
                </div>
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

    <script>

    function popup(time, day, idi) {
        var myModal = new bootstrap.Modal(document.getElementById('popup1'));
        var modalBodyText = 'Are you sure you want to reserve this time slot?';
        var modalTitleText = day + ': ' + time;
        document.querySelector('#popup1 .modal-title').textContent = modalTitleText;
        document.querySelector('#popup1 .modal-body').textContent = modalBodyText;

        myModal.show();


        document.getElementById('hiddenId').value = idi;
        document.getElementById('hiddenTime').value = time;
        document.getElementById('hiddenDay').value = day;
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