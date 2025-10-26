<?php
//u]nIRxgu_(9EfWj!
    if (!empty($_POST)) {
        $conn = new mysqli('localhost', 'uporabnik', 'u]nIRxgu_(9EfWj!', '4predmet');
        
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $email = $_POST['email'];
        $pass = $_POST['password1'];
        $name = $_POST['name'];
        $lname = $_POST['lname'];

        
        $passwordHash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = $conn->prepare("INSERT INTO uporabnik (email, pass, name, lname) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $email, $passwordHash, $name, $lname);

        if ($sql->execute()) {
            echo "<script>alert('New record created successfully');</script>";
            header('Location: sign_in.php');
            exit; 
        } else {
            echo "Error: " . $sql->error;
        }

        $sql->close();
        
        
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <br><br><br>
    <div class="row">
        <div class="col-1 col-md-4"></div>
        <div class="col-10 col-md-4">
            <div class="form-control border-dark-subtle shadow p-3 mb-5 bg-body-tertiary rounded">
                <form id="signupForm" action="" method="post">
                    <h1>Create an account</h1>
                    <br>
                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="name" placeholder="Name" required>
                    </div>
                    <hr>
                    <div class="input-group my-2">
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="lname" placeholder="Last name" required>
                    </div>
                    <hr>
                    <div class="input-group my-2">
                        <input type="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="email" placeholder="Email" required>
                    </div>
                    <hr>
                    <div class="input-group my-2">
                        <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="password1" id="password1" placeholder="Password" required>
                    </div>
                    <hr>
                    <div class="input-group my-2">
                        <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="password2" id="password2" placeholder="Confirm Password" required>
                    </div>
                    <h6>Already have an account? <a href="sign_in.php">Sign in</a></h6>
                    <div class="input-group my-2">
                        <button class="btn btn-primary" type="submit" style="width: 100%;">Register</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-1 col-md-4"></div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var password1 = document.getElementById('password1');
            var password2 = document.getElementById('password2');
            var form = document.getElementById('signupForm');

            function validatePasswords() {
                if (password1.value !== password2.value || password1.value.length < 2 || password2.value.length < 2) {
                    password1.style.border = "1px solid red";
                    password2.style.border = "1px solid red";
                    return false;
                } else {
                    password1.style.border = "1px solid #dee2e6";
                    password2.style.border = "1px solid #dee2e6";
                    return true;
                }
            }

            password1.addEventListener("keyup", validatePasswords);
            password2.addEventListener("keyup", validatePasswords);

            form.addEventListener("submit", function(event) {
                if (!validatePasswords()) {
                    event.preventDefault(); // Prevent form submission
                    alert("Passwords must match and be at least 6 characters long.");
                }
            });
        });
    </script>
</body>
</html>