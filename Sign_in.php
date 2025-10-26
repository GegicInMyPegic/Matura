<?php
    session_start(); 


    if (!empty($_POST)) {

        $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $conn->real_escape_string($_POST['email']);
        $pass = $conn->real_escape_string($_POST['pass']);

        $sql = "SELECT id, email, pass FROM uporabnik WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['pass'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $email;

                header("Location: index.php");
            } else {
                echo "<script>alert('Invalid password.');</script>";
            }
        } else {
            echo "<script>alert('No user found with that email.');</script>";
        }
        
        $conn->close();
        

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <br><br><br>      
    <div class="row">
        <div class="col-1 col-md-4"></div>
        <div class="col-10 col-md-4">
            <div class="form-control border-dark-subtle shadow p-3 mb-5 bg-body-tertiary rounded">
                <form action="" method="post">
                    <h1>Sign in</h1>
                    <br>
                    <div class="input-group my-2">
                        <input type="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="email" placeholder="Email">
                    </div>
                    <hr>
                    <div class="input-group my-2">
                        <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="pass" placeholder="Password">
                    </div>
                    <h6>Don't have an account? <a href="sign_up.php">Register</a></h6>
                    <div class="input-group my-2">
                        <button class="btn btn-primary" type="submit" style="width: 100%;"style="width: 100%;">Sign in</button>
                    </div>

                </form>
                </div>
        </div>
        <div class="col-1 col-md-4"></div>
      </div>
    
    
</body>
</html>