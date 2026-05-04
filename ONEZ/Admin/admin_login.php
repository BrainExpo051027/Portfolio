<?php
  include "connection.php";
  include "navbar.php";
?>

<!DOCTYPE html>
<html>
<head>

  <title>Student Login</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  
  <style type="text/css">
  body {
    background-color: #004528;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
  }

  .log_img {
    width: 100%;
    display: flex;
    justify-content: center;
  }

  .box1 {
    background-color: #066a3c;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.4);
    max-width: 450px;
    width: 100%;
    text-align: center;
    color: white;
  }

  .box1 h1 {
    margin-bottom: 20px;
  }

  .login {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .login input.form-control {
    width: 100%;
    max-width: 300px;
    margin-bottom: 15px;
    height: 40px;
    font-size: 16px;
    border-radius: 5px;
    border: none;
    padding-left: 10px;
  }

  .login input[type="submit"] {
    width: 100%;
    max-width: 300px;
    background-color: #ffffff;
    color: #004528;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    height: 40px;
    transition: background-color 0.3s ease;
  }

  .login input[type="submit"]:hover {
    background-color: #e2e2e2;
  }

  .box1 p {
    margin-top: 20px;
    color: white;
  }

  .box1 a {
    color: #fff;
    text-decoration: underline;
  }

  .alert-danger {
    max-width: 450px;
    margin: 20px auto;
    text-align: center;
    background-color: #de1313 !important;
    color: white;
    border-radius: 5px;
    padding: 10px;
  }
</style>

</head>
<body>

<section>
  <div class="log_img">
   <br>
    <div class="box1">
        <h1 style="text-align: center; font-size: 35px;font-family: Lucida Console;">Library Management System</h1>
        <h1 style="text-align: center; font-size: 25px;">User Login Form</h1><br>
      <form  name="login" action="" method="post">
        
        <div class="login">
          <input class="form-control" type="text" name="username" placeholder="Username" required=""> <br>
          <input class="form-control" type="password" name="password" placeholder="Password" required=""> <br>
          <input class="btn btn-default" type="submit" name="submit" value="Login" style="color: black; width: 70px; height: 30px"> 
        </div>
      
      <p style="color: white; padding-left: 15px;">
        <br><br>
        <a style="color:white;" href="">Forgot password?</a> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
        New to this website?<a style="color: white;" href="registration.html">Sign Up</a>
      </p>
    </form>
    </div>
  </div>
</section>

  <?php

    if(isset($_POST['submit']))
    {
      $count=0;
      $res=mysqli_query($conn,"SELECT * FROM `admin` WHERE username='$_POST[username]' && password='$_POST[password]';");

      $row = mysqli_fetch_assoc($res);
      $count=mysqli_num_rows($res);

      if($count==0)
      {
        ?>
              <!--
              <script type="text/javascript">
                alert("The username and password doesn't match.");
              </script> 
              -->
          <div class="alert alert-danger" style="width: 600px; margin-left: 370px; background-color: #de1313; color: white">
            <strong>The username and password doesn't match</strong>
          </div>    
        <?php
      }
      else
      {
        $_SESSION['login_user'] = $_POST['username']; 
        $_SESSION['pic'] = $row['pic'];

        ?>
          <script type="text/javascript">
            window.location="index.php"
          </script>
        <?php
      }
    }

  ?>

</body>
</html>