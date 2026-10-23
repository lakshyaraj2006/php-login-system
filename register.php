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

$username = $password = $cconfirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if username is empty
  if (empty(trim($_POST["username"]))) {
    $username_err = "Username cannot be blank";
    $_SESSION["alert"] = [
      "message" => $username_err,
      "type" => ALERT_ERROR
    ];
    header("location: register.php");
    exit;
  } else {
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set the value of param username
      $param_username = trim($_POST["username"]);

      // Try to execute 
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "This username is already taken";
          $_SESSION["alert"] = [
            "message" => $username_err,
            "type" => ALERT_ERROR
          ];
          header("location: register.php");
          exit;
        } else {
          $username = trim($_POST["username"]);
        }
      } else {
        echo "Something went wrong :(";
      }
      mysqli_stmt_close($stmt);
    }

    // Check for password
    if (empty(trim($_POST["password"]))) {
      $password_err = "Password cannot be blank";
      $_SESSION["alert"] = [
        "message" => $password_err,
        "type" => ALERT_ERROR
      ];
      header("location: register.php");
      exit;
    } else if (strlen(trim($_POST["password"])) < 5) {
      $password_err = "Password cannot be less than 5 characters";
      $_SESSION["alert"] = [
        "message" => $password_err,
        "type" => ALERT_ERROR
      ];
      header("location: register.php");
      exit;
    } else {
      $password = $_POST["password"];
    }

    // Check for confirm password
    if (empty(trim($_POST["confirm_password"]))) {
      $confirm_password_err = "Please confirm your password";
      $_SESSION["alert"] = [
        "message" => $confirm_password_err,
        "type" => ALERT_ERROR
      ];
      header("location: register.php");
      exit;
    } elseif ($_POST["confirm_password"] !== $_POST["password"]) {
      $confirm_password_err = "Passwords do not match";
      $_SESSION["alert"] = [
        "message" => $confirm_password_err,
        "type" => ALERT_ERROR
      ];
      header("location: register.php");
      exit;
    }

    // If there were no errors, go ahead and insert into the database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
      $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
      $stmt = mysqli_prepare($conn, $sql);

      if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set these parameters
        $param_username = $username;
        $param_password = password_hash(trim($_POST["password"]), PASSWORD_ARGON2ID);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt)) {
          $_SESSION["alert"] = [
            "message" => "User account created successfully",
            "type" => ALERT_SUCCESS
          ];
          header("location: login.php");
          exit;
        } else {
          echo "Something went wrong... cannot redirect :(";
        }
        mysqli_stmt_close($stmt);
      }
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

  <title>Register - PHP Login System</title>
</head>

<body>
  <?php include 'partials/_navbar.php' ?>

  <div class="container mt-4">
    <h2>Please Register Here</h2>
    <form action="" method="post">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Username">
        </div>
        <div class="form-group col-md-6">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
      </div>
      <div class="form-group">
        <label for="confirm_password">Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
      </div>
      <div class="form-group">
        <label for="inputAddress2">Address 2</label>
        <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputCity">City</label>
          <input type="text" class="form-control" id="inputCity">
        </div>
        <div class="form-group col-md-4">
          <label for="inputState">State</label>
          <select id="inputState" class="form-control">
            <option selected>Choose...</option>
            <option>...</option>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="inputZip">Zip</label>
          <input type="text" class="form-control" id="inputZip">
        </div>
      </div>
      <button type="submit" class="btn btn-primary d-flex align-items-center">
        <img src="assets/icons/login.svg" alt="registerIcon" width="20px" class="mr-2">
        <span>Register</span>
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