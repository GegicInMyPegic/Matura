<?php    
    session_start();
    if (!empty($_POST)){
        $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');
            
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }
        $time = $_POST["time"];
        $iid = $_POST["id"];
        $day = $_POST["day"];
        $uid = $_SESSION["id"];
        
        $sql = $conn->prepare("INSERT INTO rezervacija (dan, cas, uporabnik_id, igrisce_id) VALUES (?, ?, ?, ?)");
    
        $sql->bind_param("ssii", $day, $time, $uid, $iid);
    
        if ($sql->execute()) {
            echo "<script>alert('New record created successfully');</script>";
        } else {
            echo "Error: " . $sql->error;
        }
    
        $sql->close();
        
    
        $conn->close();
        header('Location: court.php?id='.$iid);
        exit;
    }
    header('Location: index.php');
    exit;
    
?>