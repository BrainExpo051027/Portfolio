<?php
  include "connection.php";
  include "navbar.php";
  
?>
<!DOCTYPE html>
<html>
<head>
  <title>Student Login</title>
  <link rel="stylesheet" href="style.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

  <style>
    body, html {
      margin: 0;
      padding: 0;
      width: 100%;
      min-height: 100vh;
      overflow-x: hidden;
    }

    section {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-image: url("images/bgforall.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding: 20px;
    }

    .box1 {
      max-width: 450px;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 30px 25px;
      border-radius: 10px;
    }

   .box1 {
  max-width: 450px;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 30px 25px;
  border-radius: 10px;
  /* Remove flex here */
  /* text-align center for headings only */
}

.box1 h1, .box1 h2 {
  text-align: center;
}

/* Center the inputs and button inside .login */
.login {
  max-width: 350px;
  margin: 0 auto;  /* center .login box */
  display: flex;
  flex-direction: column;
  gap: 15px; /* spacing between inputs */
}

.login input[type='text'],
.login input[type='password'],
.login input[type='submit'] {
  width: 100%;
  display: block;
}

/* Keep radio-group styling as is (already centered) */

.forgot-signup {
  text-align: center;
  margin-top: 20px;
  font-size: 1.3rem;
  font-weight: 600;
}

.forgot-signup a {
  color: yellow;
  text-decoration: none;
  font-weight: bold;
}


 .alert {
  max-width: 600px;
 margin: 20px auto;
 background-color: #de1313;
 color: white;
   }
   .radio-group {
  max-width: 350px;
  margin: 0 auto 20px;
  display: flex;
  justify-content: center;
  gap: 30px;
  align-items: center;
}

.radio-group input[type="radio"] {
  vertical-align: middle;
  margin-right: 8px;
}

  </style>
</head>
<body>

<section>
  <div class="box1">
    <h1>Library Borrowing System</h1>
    <h2>User Login Form</h2>

    <form name="login" method="post">
      <div class="radio-group">
        <div>
          <input type="radio" name="user" id="admin" value="admin" checked>
          <label for="admin">Admin</label>
        </div>
        <div>
          <input type="radio" name="user" id="student" value="student">
          <label for="student">Student</label>
        </div>
      </div>

      <div class="login">
        <input class="form-control" type="text" name="username" placeholder="Username" required>
        <input class="form-control" type="password" name="password" placeholder="Password" required>
        <input class="btn btn-default" type="submit" name="submit" value="Login">
      </div>

      <div class="forgot-signup">
        <a href="Student/update_password.php">Forgot password?</a><br>
        New to this website? <a href="registration.php">Sign Up</a>
      </div>
    </form>
  </div>
</section>

<?php
  if (isset($_POST['submit'])) {
    $userType = $_POST['user'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($userType == 'admin') {
      $res = mysqli_query($conn, "SELECT * FROM `admin` WHERE username='$username' && password='$password' and status='yes';");
    } else {
      $res = mysqli_query($conn, "SELECT * FROM `student` WHERE username='$username' && password='$password';");
    }

    $count = mysqli_num_rows($res);
    $row = mysqli_fetch_assoc($res);

    if ($count == 0) {
      echo '<div class="alert alert-danger"><strong>The username and password doesn\'t match</strong></div>';
    } else {
      $_SESSION['login_user'] = $username;
      $_SESSION['pic'] = $row['pic'];

      echo "<script>window.location='" . ($userType == 'admin' ? "Admin/profile.php" : "Student/index.php") . "'</script>";
    }
  }
?>

</body>
</html>
