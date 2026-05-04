<?php
  session_start();
  include "connection.php"; 
?>

<!DOCTYPE html>
<html>
<head>
  <title>Navbar</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>

<?php
  $count = ['total' => 0]; // Default value to avoid undefined index
  if (isset($_SESSION['login_user'])) {
    $r = mysqli_query($conn, "SELECT COUNT(status) AS total FROM message WHERE status='no' AND username='$_SESSION[login_user]' AND sender='admin';");
    if ($r) {
      $count = mysqli_fetch_assoc($r);
    }
  }
?>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand active">LIBRARY BORROWING SYSTEM</a>
    </div>

    <ul class="nav navbar-nav">
      <li><a href="index.php">HOME</a></li>
      <li><a href="books.php">BOOKS</a></li>
      <li><a href="feedback.php">FEEDBACK</a></li>
    </ul>

    <?php if (isset($_SESSION['login_user'])): ?>
      <ul class="nav navbar-nav">
        <li><a href="profile.php">PROFILE</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="message.php">
            <span class="glyphicon glyphicon-envelope"></span>
            <span class="badge bg-green">
              <?php echo $count['total']; ?>
            </span>
          </a>
        </li>
        <li>
          <a href="#">
            <div style="color: white">
              <?php
                $pic = isset($_SESSION['pic']) ? $_SESSION['pic'] : 'default.jpg';
                echo "<img class='img-circle profile_img' height=30 width=30 src='images/$pic'>";
                echo " " . $_SESSION['login_user'];
              ?>
            </div>
          </a>
        </li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"> LOGOUT</span></a></li>
      </ul>
    <?php else: ?>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="../login.php"><span class="glyphicon glyphicon-log-in"> LOGIN</span></a></li>
        <li><a href="registration.php"><span class="glyphicon glyphicon-user"> SIGN UP</span></a></li>
      </ul>
    <?php endif; ?>
  </div>
</nav>

<?php
if (isset($_SESSION['login_user'])) {
  $day = 0;
  $exp = '<p style="color:yellow; background-color:red;">EXPIRED</p>';
  $res = mysqli_query($conn, "SELECT * FROM `issue_book` WHERE username = '$_SESSION[login_user]' AND approve = '$exp';");

  while ($row = mysqli_fetch_assoc($res)) {
    $d = strtotime($row['return']);
    $c = strtotime(date("Y-m-d"));
    $diff = $c - $d;

    if ($diff >= 0) {
      $day += floor($diff / (60 * 60 * 24)); // Days
    }
  }

  $_SESSION['fine'] = $day * 0.10;
}
?>

</body>
</html>
