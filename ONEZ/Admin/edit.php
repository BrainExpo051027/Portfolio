<?php
	include "navbar.php";
	include "connection.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>edit profile</title>
	<style type="text/css">
  body {
    background-color: #004528;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  h2 {
    text-align: center;
    color: #fff;
    margin-top: 30px;
  }

  .profile_info {
    text-align: center;
    color: white;
    margin-top: 10px;
  }

  .form1 {
  max-width: 400px;
  margin: 1px auto; /* Reduced from 40px for higher placement */
  padding: 30px;
  background-color: #066a3c;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.4);
}

  .form1 label {
    color: white;
    font-size: 16px;
    margin-top: 10px;
    display: block;
  }

  .form-control {
    width: 100%;
    height: 40px;
    margin-bottom: 15px;
    padding-left: 10px;
    border-radius: 5px;
    border: none;
    font-size: 15px;
  }

  .form1 button {
    width: 100%;
    height: 40px;
    background-color: #fff;
    color: #004528;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s;
  }

  .form1 button:hover {
    background-color: #e6e6e6;
  }
</style>

</head>
<body style="background-color: #004528;">

	<h2 style="text-align: center;color: #fff;">Edit Information</h2>
	<?php
		
		$sql = "SELECT * FROM admin WHERE username='$_SESSION[login_user]'";
		$result = mysqli_query($conn,$sql) or die (mysql_error());

		while ($row = mysqli_fetch_assoc($result)) 
		{
			$first=$row['first'];
			$last=$row['last'];
			$username=$row['username'];
			$password=$row['password'];
			$email=$row['email'];
			$contact=$row['contact'];
		}

	?>

	<div class="profile_info" style="text-align: center;">
		<span style="color: white;">Welcome,</span>	
		<h4 style="color: white;"><?php echo $_SESSION['login_user']; ?></h4>
	</div><br><br>
	
	<div class="form1">
  <form action="" method="post" enctype="multipart/form-data">

    <label><b>Upload Profile Picture</b></label>
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

    <button type="submit" name="submit">Save</button>
  </form>
</div>

	<?php 

		if(isset($_POST['submit']))
		{
			move_uploaded_file($_FILES['file']['tmp_name'],"images/".$_FILES['file']['name']);

			$first=$_POST['first'];
			$last=$_POST['last'];
			$username=$_POST['username'];
			$password=$_POST['password'];
			$email=$_POST['email'];
			$contact=$_POST['contact'];
			$pic=$_FILES['file']['name'];

			$sql1= "UPDATE admin SET pic='$pic', first='$first', last='$last', username='$username', password='$password', email='$email', contact='$contact' WHERE username='".$_SESSION['login_user']."';";

			if(mysqli_query($conn,$sql1))
			{
				?>
					<script type="text/javascript">
						alert("Saved Successfully.");
						window.location="profile.php";
					</script>
				<?php
			}
		}
 	?>
</body>
</html>

