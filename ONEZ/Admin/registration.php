<?php
  include "connection.php";
  include "navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Registration</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS & JS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <style type="text/css">
  body {
    background-color: #004528;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    color: white;
  }

  .reg_img {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
  }

  .box2 {
    background-color: #066a3c;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.4);
    max-width: 450px;
    width: 100%;
    text-align: center;
  }

  .box2 h1 {
    color: #fff;
    margin-bottom: 25px;
    font-size: 26px;
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
    background-color: #dddddd;
  }

  @media (max-width: 576px) {
    .box2 {
      padding: 30px 20px;
    }
  }
</style>

</head>
<body>

<section>
  <div class="reg_img">
    <div class="box2">
      <h1>Library Borrowing System</h1>
      <h1 style="font-size: 22px;">Admin Registration</h1>

      <form name="Registration" action="" method="post">
        <div class="login">
          <input class="form-control" type="text" name="first" placeholder="First Name" required>
          <input class="form-control" type="text" name="last" placeholder="Last Name" required>
          <input class="form-control" type="text" name="username" placeholder="Username" required>
          <input class="form-control" type="password" name="password" placeholder="Password" required>
          <input class="form-control" type="text" name="email" placeholder="Email" required>
          <input class="form-control" type="text" name="contact" placeholder="Phone No" required maxlength="11">
          <input class="btn btn-default" type="submit" name="submit" value="Sign Up">
        </div>
      </form>
    </div>
  </div>
</section>

<?php
  if(isset($_POST['submit'])) {
    $count = 0;
    $sql = "SELECT username FROM `admin`";
    $res = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($res)) {
      if($row['username'] == $_POST['username']) {
        $count++;
      }
    }

    if($count == 0) {
      mysqli_query($conn, "INSERT INTO `admin` VALUES('', '$_POST[first]', '$_POST[last]', '$_POST[username]', '$_POST[password]', '$_POST[email]', '$_POST[contact]', 'p.jpg', '');");
?>
      <script type="text/javascript">
        window.location = "../login.php";
      </script>
<?php
    } else {
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
