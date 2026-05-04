<?php
include "navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Approve Request</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <style type="text/css">
    .srch {
      padding-left: 850px;
    }
    body {
      font-family: "Lato", sans-serif;
      transition: background-color .5s;
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
      color: #f1f1f1;
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
    }
    .h:hover {
      color:white;
      width: 300px;
      height: 50px;
      background-color: #00544c;
    }
  </style>
</head>
<body>

<!--_________________sidenav_______________-->
  
  <div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <div style="color: white; margin-left: 60px; font-size: 20px;">

            <?php
            if(isset($_SESSION['login_user'])) { 
                echo "<img class='img-circle profile_img' height=120 width=120 src='images/".$_SESSION['pic']."'>";
                echo "</br></br>";
                echo "Welcome ".$_SESSION['login_user']; 
            }
            ?>
        </div>
<br><br>
  <div class="h"><a href="request.php">Book Request</a></div>
  <div class="h"><a href="issue_info.php">Issue Information</a></div>
  <div class="h"><a href="expired.php">Expired List</a></div>
</div>

<div id="main">
  
  <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Open</span>

<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "300px";
  document.getElementById("main").style.marginLeft = "300px";
  document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
  document.body.style.backgroundColor = "white";
}
</script>

  <!--__________________________search bar________________________-->
<div class="container">
  <h2 style="float: left;">Search one username at a time to approve the request.</h2>
  <div style="float:right;" class="srch">
    <form class="navbar-form" method="post" name="form1">
      <input class="form-control" type="text" name="search" placeholder="Admin username.." required="">
      <button style="background-color: #6db6b9e6;" type="submit" name="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search"></span>
      </button>
    </form>
  </div>
  <br>
  <h2>New Request</h2>

  <?php
  // Handle approve/reject buttons first
  if (isset($_POST['submit1']) && isset($_POST['selected_user'])) {
      $username = mysqli_real_escape_string($conn, $_POST['selected_user']);
      mysqli_query($conn, "DELETE FROM admin WHERE username='$username' AND status='';");
  }

  if (isset($_POST['submit2']) && isset($_POST['selected_user'])) {
      $username = mysqli_real_escape_string($conn, $_POST['selected_user']);
      mysqli_query($conn, "UPDATE admin SET status='yes' WHERE username='$username';");
  }

  // Search or show all pending requests
  if (isset($_POST['submit'])) {
      $search = mysqli_real_escape_string($conn, $_POST['search']);
      $q = mysqli_query($conn, "SELECT first, last, username, email, contact FROM admin WHERE username LIKE '%$search%' AND status=''");
      if (mysqli_num_rows($q) == 0) {
          echo "<p>Sorry! No new request found with that username. Try searching again.</p>";
      } else {
          echo "<table class='table table-bordered table-hover'>";
          echo "<tr style='background-color: #6db6b9e6;'>";
          echo "<th>First Name</th>";
          echo "<th>Last Name</th>";
          echo "<th>Username</th>";
          echo "<th>Email</th>";
          echo "<th>Contact</th>";
          echo "<th>Actions</th>";
          echo "</tr>";

          while ($row = mysqli_fetch_assoc($q)) {
              echo "<tr>";
              echo "<td>".$row['first']."</td>";
              echo "<td>".$row['last']."</td>";
              echo "<td>".$row['username']."</td>";
              echo "<td>".$row['email']."</td>";
              echo "<td>".$row['contact']."</td>";
              echo "<td>
                <form method='post' style='display:inline-block; margin:0;'>
                  <input type='hidden' name='selected_user' value='".htmlspecialchars($row['username'])."'>
                  <button type='submit' name='submit1' class='btn btn-danger' title='Reject'>
                    <span class='glyphicon glyphicon-remove-sign'></span>
                  </button>
                </form>
                <form method='post' style='display:inline-block; margin:0;'>
                  <input type='hidden' name='selected_user' value='".htmlspecialchars($row['username'])."'>
                  <button type='submit' name='submit2' class='btn btn-success' title='Approve'>
                    <span class='glyphicon glyphicon-ok-sign'></span>
                  </button>
                </form>
              </td>";
              echo "</tr>";
          }
          echo "</table>";
      }
  } else {
      // Show all new requests
      $res = mysqli_query($conn, "SELECT first, last, username, email, contact FROM admin WHERE status='';");
      echo "<table class='table table-bordered table-hover'>";
      echo "<tr style='background-color: #6db6b9e6;'>";
      echo "<th>First Name</th>";
      echo "<th>Last Name</th>";
      echo "<th>Username</th>";
      echo "<th>Email</th>";
      echo "<th>Contact</th>";
      echo "<th>Actions</th>";
      echo "</tr>";
      while ($row = mysqli_fetch_assoc($res)) {
          echo "<tr>";
          echo "<td>".$row['first']."</td>";
          echo "<td>".$row['last']."</td>";
          echo "<td>".$row['username']."</td>";
          echo "<td>".$row['email']."</td>";
          echo "<td>".$row['contact']."</td>";
          echo "<td>
            <form method='post' style='display:inline-block; margin:0;'>
              <input type='hidden' name='selected_user' value='".htmlspecialchars($row['username'])."'>
              <button type='submit' name='submit1' class='btn btn-danger' title='Reject'>
                <span class='glyphicon glyphicon-remove-sign'></span>
              </button>
            </form>
            <form method='post' style='display:inline-block; margin:0;'>
              <input type='hidden' name='selected_user' value='".htmlspecialchars($row['username'])."'>
              <button type='submit' name='submit2' class='btn btn-success' title='Approve'>
                <span class='glyphicon glyphicon-ok-sign'></span>
              </button>
            </form>
          </td>";
          echo "</tr>";
      }
      echo "</table>";
  }
  ?>

</div>
</body>
</html>
