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
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  width: 100%;
  font-family: Arial, sans-serif;
  overflow: hidden;
}

section {
  height: 100vh;
  width: 100vw;
  background-image: url("images/bgforall.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
}

.box {
  width: 450px;
  background-color: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
}

form {
  width: 100%;
}

form p {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 20px;
}

.radio-group {
  display: flex;
  justify-content: center;
  gap: 40px;
  align-items: center;
  margin-bottom: 20px;
}

.radio-group label {
  font-size: 16px;
  font-weight: 500;
  margin-left: 8px;
}

input[type="radio"] {
  transform: scale(1.2);
  vertical-align: middle;
}

button[type="submit"] {
  margin-top: 10px;
  width: 80px;
  height: 35px;
  font-weight: 700;
  color: black;
  background-color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}



  </style>   
</head>
<body>

<section>
  <div class="box">
     <form  name="signup" action="" method="post">
        <b><p style="padding-left: 10px;font-size: 15px; font-weight: 700;">Sign Up as:</p></b>
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


         <button class="btn btn-default" type="submit" name="submit1" style="color: black; font-weight: 700; width: 70px; height: 30px;">Ok</button>
       </form>
  </div>
  <?php 
    if (isset($_POST['submit1'])) {
        if ($_POST['user']=='admin') {
           ?>
          <script type="text/javascript">
            window.location="Admin/registration.php"
          </script>
            <?php 
        }else{
          ?>
          <script type="text/javascript">
            window.location="Student/registration.php"
          </script>
        <?php
        }
    }

   ?>

 </section>

</body>
</html>