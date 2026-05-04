<?php
  include "connection.php";
  include "navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Books</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style type="text/css">
    body {
      background-color: #024629;
      font-family: "Lato", sans-serif;
      transition: background-color .5s;
      margin: 0;
      padding: 0;
      color: white;
    }

    .srch {
      padding-left: 1000px;
    }

    .sidenav {
      height: 100%;
      margin-top: 50px;
      width: 0;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0;
      background-color: #222;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
    }

    .sidenav a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
      transition: 0.3s;
    }

    .sidenav a:hover {
      color: white;
    }

    .sidenav .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
      margin-left: 50px;
    }

    #main {
      transition: margin-left .5s;
      padding: 16px;
    }

    @media screen and (max-height: 450px) {
      .sidenav {padding-top: 15px;}
      .sidenav a {font-size: 18px;}
    }

    .img-circle {
      margin-left: 20px;
      border-radius: 50%;
      border: 2px solid #fff;
    }

    .h:hover {
      color: white;
      width: 300px;
      height: 50px;
      background-color: #00544c;
    }

    /* Center and style the form container */
    .book {
    width: 400px;
    margin: 40px auto;  /* center form with some spacing */
    padding: 20px;
    background-color: #013920;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.7);
}

.form-control, .btn-default {
    width: 100%;
    height: 40px;
    padding: 8px 12px;
    margin: 10px 0;  /* space between inputs/buttons */
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    border: none;
    transition: background-color 0.3s ease;
    display: block;
}

.form-control {
    background-color: #080707;
    color: white;
}

.form-control:focus {
    background-color: #004d33;
    outline: none;
    color: white;
}

.btn-default {
    background-color: #00544c;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

.btn-default:hover {
    background-color: #003d2f;
}



    /* Container heading style */
    .container h2 {
      color: white;
      font-family: "Lucida Console", monospace;
      margin-bottom: 30px;
      font-weight: bold;
      text-align: center;
    }
  </style>
</head>

<body>
  <!--_________________sidenav_______________-->

  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

    <div style="color: white; margin-left: 60px; font-size: 20px;">
      <?php
      if (isset($_SESSION['login_user'])) {
        echo "<img class='img-circle profile_img' height=120 width=120 src='images/" . $_SESSION['pic'] . "'>";
        echo "<br><br>";
        echo "Welcome " . $_SESSION['login_user'];
      }
      ?>
    </div><br><br>

    <div class="h"><a href="add.php">Add Books</a></div>
    <div class="h"><a href="request.php">Book Request</a></div>
    <div class="h"><a href="issue_info.php">Issue Information</a></div>
  </div>

  <div id="main">
    <span style="font-size:30px;cursor:pointer; color: black;" onclick="openNav()">&#9776; Open</span>
    <div class="container" style="text-align: center;">
      <h2><b>Add New Books</b></h2>

      <form class="book" action="" method="post">

        <input type="text" name="bid" class="form-control" placeholder="Book Id" required>
        <input type="text" name="name" class="form-control" placeholder="Book Name" required>
        <input type="text" name="authors" class="form-control" placeholder="Authors Name" required>
        <input type="text" name="edition" class="form-control" placeholder="Edition" required>
        <input type="text" name="status" class="form-control" placeholder="Status" required>
        <input type="text" name="quantity" class="form-control" placeholder="Quantity" required>
        <input type="text" name="department" class="form-control" placeholder="Department" required>

        <button class="btn btn-default" type="submit" name="submit">ADD</button>
      </form>
    </div>

    <?php
    if (isset($_POST['submit'])) {
      if (isset($_SESSION['login_user'])) {
        mysqli_query($conn, "INSERT INTO books VALUES ('','$_POST[bid]', '$_POST[name]', '$_POST[authors]', '$_POST[edition]', '$_POST[status]', '$_POST[quantity]', '$_POST[department]')");
    ?>
        <script type="text/javascript">
          alert("Book Added Successfully.");
        </script>
      <?php
      } else {
      ?>
        <script type="text/javascript">
          alert("You need to login first.");
        </script>
    <?php
      }
    }
    ?>
  </div>

  <script>
    function openNav() {
      document.getElementById("mySidenav").style.width = "300px";
      document.getElementById("main").style.marginLeft = "300px";
      document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    }

    function closeNav() {
      document.getElementById("mySidenav").style.width = "0";
      document.getElementById("main").style.marginLeft = "0";
      document.body.style.backgroundColor = "#024629";
    }
  </script>

</body>

</html>
