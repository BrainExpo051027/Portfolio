<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>
		 Library Borrowing System
	</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  
<style type="text/css">
	nav
	{
		float: right;
		word-spacing: 30px;
		padding: 20px;
	}
	nav li 
	{
		display: inline-block;
		line-height: 80px;
	}
</style>
</head>


<body>
  <div class="wrapper">
    <header>
      <div class="logo">
        <img src="images/9.png" alt="Library Logo">
        <h1><a href="../index.php" style="text-decoration: none; color: inherit;">LIBRARY BORROWING SYSTEM</a></h1>
      </div>

      <nav>
        <ul>
          <li><a href="index.php">HOME</a></li>
          <li><a href="books.php">BOOKS</a></li>
          <?php if(isset($_SESSION['login_user'])): ?>
            <li><a href="logout.php">LOGOUT</a></li>
            <li><a href="feedback.php">FEEDBACK</a></li>
          <?php else: ?>
            <li><a href="login.php">LOGIN</a></li>
            <li><a href="registration.php">SIGN-UP</a></li>
            <li><a href="feedback.php">FEEDBACK</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>

    <section>
      <div class="sec_img">
        <div class="box">
          <h1 style="text-align: center; font-size: 35px;">Welcome to library</h1><br>
          <h1 style="text-align: center; font-size: 25px;">Opens at: 09:00 A.M</h1><br>
          <h1 style="text-align: center; font-size: 25px;">Closes at: 05:00 P.M</h1>
        </div>
      </div>
    </section>

    <footer>
      <?php include "footer.php"; ?>
    </footer>
  </div>
</body>

</html>