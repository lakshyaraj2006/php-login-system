<?php
// This script will handle login
session_start();

// Check if the user is already loggedin
if (isset($_SESSION["user"])) {
    header("location: index.php");
    exit;
}
require_once "config.php";
require_once "constants.php";

$id = $username = $password = $hashed_password = "";
$err = "";

// if request method is post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"])) || empty(trim($_POST["password"]))) {
        $err = "All fields are required";
        $_SESSION["alert"] = [
            "message" => $err,
            "type" => ALERT_ERROR
        ];
        header("location: login.php");
        exit;
    } else {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
    }

    if (empty($err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            // Try to execute 
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // this means the password is correct, allow user to login
                            session_regenerate_id();
                            $_SESSION["user"] = [
                                "id" => $id,
                                "username" => $username
                            ];
                            $_SESSION["loggedin"] = true;

                            // Redirect user to welcome page
                            $_SESSION["alert"] = [
                                "message" => "Logged in successfully",
                                "type" => ALERT_SUCCESS
                            ];
                            header("location: index.php");
                            exit;
                        }
                    }
                } else {
                    $err = "Invalid username or password";
                    $_SESSION["alert"] = [
                        "message" => $err,
                        "type" => ALERT_ERROR
                    ];
                    header("location: login.php");
                    exit;
                }
            } else {
                echo "Something went wrong :(";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Login - PHP Login System</title>
</head>

<body>
    <?php include 'partials/_navbar.php' ?>

    <div class="container mt-4">
        <h2>Please Login Here</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary d-flex align-items-center">
                <img src="assets/icons/login.svg" alt="loginIcon" width="20px" class="mr-2">
                <span>Login</span>
            </button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>