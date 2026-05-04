<?php
  include "connection.php";
  include "navbar.php";
?>

<!DOCTYPE html>
<html>
<head>

  <title>Student Registration</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >

  <style type="text/css">
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  html, body {
    height: 100%;
    font-family: Arial, sans-serif;
    background: url("images/bgforall.jpg") no-repeat center center fixed;
    background-size: cover;
  }

  section {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
  }

  .reg_img {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }

  .box2 {
    background-color: rgba(0, 0, 0, 0.6); /* <-- changed from 0 to 0.6 */
    padding: 40px;
    border-radius: 10px;
    color: white;
    width: 100%;
    max-width: 500px;
    text-align: center;
  }

  .box2 h1 {
    text-shadow: 1px 1px 2px black;
  }

  .login {
    display: flex;
    flex-direction: column;
    gap: 8px; /* reduce spacing between inputs */
    align-items: center;
  }

  .form-control {
    width: 100%;
    max-width: 400px;
    height: 40px;
    font-size: 16px;
  }

  .btn {
    width: 100px;
    font-weight: bold;
    color: black;
    background-color: #ddd;
    border: none;
    height: 35px;
  }
  </style>

</head>
<body>

<section>
  <div class="reg_img">

    <div class="box2">
        <h1 style="text-align: center; font-size: 35px;font-family: Lucida Console;">Library Borrowing System</h1>
        <h1 style="text-align: center; font-size: 25px;">User Registration Form</h1>

      <form name="Registration" action="" method="post">
        <div class="login">
          <input class="form-control" type="text" name="first" placeholder="First Name" required=""> 
          <input class="form-control" type="text" name="last" placeholder="Last Name" required=""> 
          <input class="form-control" type="text" name="username" placeholder="Username" required=""> 
          <input class="form-control" type="password" name="password" placeholder="Password" required=""> 
          <input class="form-control" type="text" name="roll" placeholder="Roll No" required="">
          <input class="form-control" type="text" name="email" placeholder="Email" required="">
          <input class="form-control" type="text" name="contact" placeholder="Phone No" required="" maxlength="11">

          <input class="btn btn-default" type="submit" name="submit" value="Sign Up" style="color: black; width: 70px; height: 30px">
        </div>
      </form>
     
    </div>
  </div>
</section>

<?php

if(isset($_POST['submit']))
{
    $count=0;

    $sql="SELECT username from `student`";
    $res=mysqli_query($conn,$sql);

    while($row=mysqli_fetch_assoc($res))
    {
      if($row['username']==$_POST['username'])
      {
        $count=$count+1;
      }
    }
    if($count==0)
    {
      // Hash the password before inserting
      $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

      // Use prepared statement to be safe (optional but recommended)
      $stmt = $conn->prepare("INSERT INTO `student` (first, last, username, password, roll, email, contact, pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $default_pic = 'p.jpg';

      $stmt->bind_param(
        "ssssssss", 
        $_POST['first'], 
        $_POST['last'], 
        $_POST['username'], 
        $hashed_password, 
        $_POST['roll'], 
        $_POST['email'], 
        $_POST['contact'], 
        $default_pic
      );

      $stmt->execute();
      $stmt->close();

      ?>
      <script type="text/javascript">
        window.location="../login.php"
      </script>
      <?php
    }
    else
    {
      ?>
      <script type="text/javascript">
        alert("The username already exists.");
      </script>
      <?php
    }
}
?>


</body>
</html>
