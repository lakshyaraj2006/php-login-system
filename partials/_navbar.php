<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">PHP Login System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact Us</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0 mr-3">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>
    </form>
    <ul class="navbar-nav">
      <?php if (!isset($_SESSION["user"])): ?>
        <li class="nav-item">
          <a class="btn btn-danger mr-2 d-flex align-items-center" href="register.php">
            <img src="assets/icons/login.svg" alt="registerIcon" width="20px" class="mr-2">
            <span>Register</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="btn btn-danger d-flex align-items-center" href="login.php">
            <img src="assets/icons/login.svg" alt="loginIcon" width="20px" class="mr-2">
            <span>Login</span>
          </a>
        </li>
      <?php else: ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle font-weight-bold active d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="assets/icons/user.svg" alt="userIcon" width="40px" class="mr-2">
            Welcome - <?php echo $_SESSION["user"]["username"] ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item d-flex align-items-center" href="logout.php">
              <img src="assets/icons/logout.svg" alt="logoutIcon" width="20px" class="mr-2">
              <span>Logout</span>
            </a>
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<?php include "partials/_alert.php" ?>