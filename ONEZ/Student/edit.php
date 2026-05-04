<?php
	include "navbar.php";
	include "connection.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Profile</title>
	<style type="text/css">
		body {
			background-color: #004528;
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			color: white;
		}
		h2 {
			text-align: center;
			margin-top: 30px;
			color: #fff;
		}
		.profile_info {
			text-align: center;
			margin-top: 20px;
		}
		.form-container {
			max-width: 500px;
			margin: 40px auto;
			background-color: #066a3c;
			padding: 30px;
			border-radius: 10px;
			box-shadow: 0 0 15px rgba(0,0,0,0.5);
		}
		label {
			display: block;
			margin-top: 15px;
			font-size: 16px;
		}
		.form-control {
			width: 100%;
			height: 38px;
			padding: 5px 10px;
			margin-top: 5px;
			border: none;
			border-radius: 5px;
			box-sizing: border-box;
		}
		input[type="file"] {
			margin-top: 10px;
			color: white;
		}
		.btn {
			background-color: #fff;
			color: #004528;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			cursor: pointer;
			font-weight: bold;
			margin-top: 20px;
			transition: background-color 0.3s;
		}
		.btn:hover {
			background-color: #ddd;
		}
	</style>
</head>
<body>

	<h2>Edit Information</h2>

	<?php
		$sql = "SELECT * FROM student WHERE username='$_SESSION[login_user]'";
		$result = mysqli_query($conn, $sql);

		while ($row = mysqli_fetch_assoc($result)) {
			$first = $row['first'];
			$last = $row['last'];
			$username = $row['username'];
			$password = $row['password'];
			$email = $row['email'];
			$contact = $row['contact'];
		}
	?>

	<div class="profile_info">
		<span>Welcome,</span>
		<h4><?php echo $_SESSION['login_user']; ?></h4>
	</div>

	<div class="form-container">
		<form action="" method="post" enctype="multipart/form-data">
			<label><b>Profile Picture</b></label>
			<input class="form-control" type="file" name="file">

			<label><b>First Name</b></label>
			<input class="form-control" type="text" name="first" value="<?php echo $first; ?>">

			<label><b>Last Name</b></label>
			<input class="form-control" type="text" name="last" value="<?php echo $last; ?>">

			<label><b>Username</b></label>
			<input class="form-control" type="text" name="username" value="<?php echo $username; ?>">

			<label><b>Password</b></label>
			<input class="form-control" type="text" name="password" value="<?php echo $password; ?>">

			<label><b>Email</b></label>
			<input class="form-control" type="text" name="email" value="<?php echo $email; ?>">

			<label><b>Contact No</b></label>
			<input class="form-control" type="text" name="contact" value="<?php echo $contact; ?>">

			<button class="btn" type="submit" name="submit">Save</button>
		</form>
	</div>

	<?php 
		if (isset($_POST['submit'])) {
			move_uploaded_file($_FILES['file']['tmp_name'], "images/" . $_FILES['file']['name']);

			$first = $_POST['first'];
			$last = $_POST['last'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$contact = $_POST['contact'];
			$pic = $_FILES['file']['name'];

			$sql1 = "UPDATE student SET pic='$pic', first='$first', last='$last', username='$username', password='$password', email='$email', contact='$contact' WHERE username='".$_SESSION['login_user']."';";

			if (mysqli_query($conn, $sql1)) {
				echo "<script>alert('Saved Successfully.'); window.location='profile.php';</script>";
			}
		}
	?>
</body>
</html>
