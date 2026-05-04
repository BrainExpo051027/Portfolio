<?php
  include "connection.php";
  include "navbar.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Book Request</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .srch {
      padding-left: 1000px;
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
    }

    .h:hover {
      color:white;
      width: 300px;
      height: 50px;
      background-color: #00544c;
    }

    th, td, input {
      width: 100px;
      text-align: center;
    }

    .status-pending {
      color: orange;
      font-weight: bold;
    }

    .status-approved {
      color: green;
      font-weight: bold;
    }

    .status-declined {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>
<!-- Side Navigation -->
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <div style="color: white; margin-left: 60px; font-size: 20px;">
    <?php
      if (isset($_SESSION['login_user'])) {
        echo "<img class='img-circle profile_img' height=120 width=120 src='images/".$_SESSION['pic']."'>";
        echo "<br><br>Welcome ".$_SESSION['login_user'];
      }
    ?>
  </div><br><br>

  <div class="h"> <a href="books.php">Books</a></div>
  <div class="h"> <a href="request.php">Book Request</a></div>
  <div class="h"> <a href="issue_info.php">Issue Information</a></div>
  <div class="h"><a href="expired.php">Expired List</a></div>
</div>

<!-- Main Content -->
<div id="main">
  <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Open</span>
  <div class="container">
    <br><br>
    <?php
      if (isset($_SESSION['login_user'])) {
        $q = mysqli_query($conn, "SELECT * FROM issue_book WHERE username='$_SESSION[login_user]' ORDER BY bid DESC;");

        if (mysqli_num_rows($q) == 0) {
          echo "You have no book requests.";
        } else {
    ?>
    <form method="POST">
      <table class='table table-bordered table-hover'>
        <tr style='background-color: #6db6b9e6;'>
          <th>Select</th>
          <th>Book-ID</th>
          <th>Approve Status</th>
          <th>Issue Date</th>
          <th>Return Date</th>
        </tr>

        <?php
          while ($row = mysqli_fetch_assoc($q)) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='check[]' value='{$row['id']}'></td>";
            echo "<td>{$row['bid']}</td>";

            // Display status with color
            echo "<td>";
            if ($row['approve'] == '') {
              echo "<span class='status-pending'>Pending</span>";
            } elseif (strtolower($row['approve']) == 'yes') {
              echo "<span class='status-approved'>Approved</span>";
            } elseif (strtolower($row['approve']) == 'no') {
              echo "<span class='status-declined'>Declined</span>";
            } else {
              echo htmlspecialchars($row['approve']);
            }
            echo "</td>";

            echo "<td>{$row['issue']}</td>";
            echo "<td>{$row['return']}</td>";
            echo "</tr>";
          }
        ?>
      </table>
      <p align="center"><button type="submit" name="delete" class="btn btn-success">Delete Pending Requests</button></p>
    </form>
    <?php
        }
      } else {
        echo "<br><br><h2><b>Please login first to see the request information.</b></h2>";
      }
    ?>
  </div>
</div>

<!-- Delete only Pending Requests -->
<?php
  if (isset($_POST['delete']) && isset($_POST['check'])) {
    foreach ($_POST['check'] as $delete_id) {
      mysqli_query($conn, "DELETE FROM issue_book WHERE bid='$delete_id' AND username='$_SESSION[login_user]' AND approve='';");
    }
  }
?>

<script>
  function openNav() {
    document.getElementById("mySidenav").style.width = "300px";
    document.getElementById("main").style.marginLeft = "300px";
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
  }

  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
    document.body.style.backgroundColor = "white";
  }
</script>
</body>
</html>
