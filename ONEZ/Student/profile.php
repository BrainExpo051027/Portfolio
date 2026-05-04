<?php 
	include "connection.php";
	include "navbar.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<style type="text/css">
		body {
			background-color: #004528;
			font-family: Arial, sans-serif;
			color: white;
			margin: 0;
			padding: 0;
		}
		.container {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			padding: 20px;
		}
		.wrapper {
			background-color: #066a3c;
			padding: 30px;
			border-radius: 10px;
			box-shadow: 0 0 15px rgba(0,0,0,0.5);
			max-width: 400px;
			width: 100%;
		}
		.profile-img {
			border-radius: 50%;
			box-shadow: 0 0 10px rgba(0,0,0,0.3);
			margin-bottom: 15px;
		}
		h2, h4 {
			margin: 10px 0;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}
		td {
			padding: 10px;
			border: 1px solid #ffffff33;
		}
		.btn {
			background-color: #fff;
			color: #004528;
			border: none;
			padding: 8px 12px;
			border-radius: 5px;
			cursor: pointer;
			transition: 0.3s;
			float: right;
			margin-bottom: 20px;
		}
		.btn:hover {
			background-color: #ddd;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="wrapper">
			<form action="" method="post">
				<button class="btn" name="submit1">Edit</button>
			</form>
			<?php
				if(isset($_POST['submit1'])) {
					echo "<script>window.location='edit.php'</script>";
				}
				$q=mysqli_query($conn,"SELECT * FROM student where username='$_SESSION[login_user]';");
				$row=mysqli_fetch_assoc($q);
				echo "<div style='text-align: center'>
					<img class='profile-img' height=110 width=110 src='images/".$_SESSION['pic']."'>
				</div>";
			?>
			<div style="text-align: center;"> <b>Welcome,</b>
				<h4><?php echo $_SESSION['login_user']; ?></h4>
			</div>
			<?php
				echo "<table>";
				echo "<tr><td><b>First Name:</b></td><td>".$row['first']."</td></tr>";
				echo "<tr><td><b>Last Name:</b></td><td>".$row['last']."</td></tr>";
				echo "<tr><td><b>Username:</b></td><td>".$row['username']."</td></tr>";
				echo "<tr><td><b>Password:</b></td><td>".$row['password']."</td></tr>";
				echo "<tr><td><b>Email:</b></td><td>".$row['email']."</td></tr>";
				echo "<tr><td><b>Contact:</b></td><td>".$row['contact']."</td></tr>";
				echo "</table>";
			?>
		</div>
	</div>
</body>
</html>
