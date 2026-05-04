<?php
  include "navbar.php";
  include "connection.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Feedback</title>
	
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
   <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" >
    <style type="text/css">
    	html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  overflow-x: hidden; /* Allow vertical scroll only */
}

body {
  background-image: url("images/bgforall.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  font-family: Arial, sans-serif;
  color: white;
}

.wrapper {
  max-width: 900px;
  margin: 40px auto;
  padding: 20px;
  background-color: rgba(0, 0, 0, 0.8);
  color: white;
  border-radius: 8px;
  box-sizing: border-box;
}

.form-control {
  height: 40px;              /* Standard input height */
  width: 60%;
  font-size: 16px;
  padding: 5px 10px;
  box-sizing: border-box;
  overflow: hidden;          /* Prevent scrolling */
  resize: none;              /* Prevent resize drag handle */
}

input[type="submit"] {
  height: 40px;
  width: 100px;
  display: inline-block;
  vertical-align: middle;
  cursor: pointer;
  font-size: 16px;
  margin-top: 10px;
}

.scroll {
  width: 100%;
  max-height: 300px;
  overflow-y: auto;         /* Keep only this scroll if needed */
  margin-top: 30px;
  border-radius: 6px;
  border: 1px solid #ccc;
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}



    </style>
</head>
<body>

	<div class="wrapper">
		<h4>If you have any suggesions or questions please comment below.</h4>
		<form style="" action="" method="post">
			<input class="form-control" type="text" name="comment" placeholder="Write something..."><br>	
			<input class="btn btn-default" type="submit" name="submit" value="Comment" style="width: 100px; height: 35px;">		
		</form>
	
<br><br>
	<div class="scroll">
		
		<?php
			if(isset($_POST['submit']))
			{
				$sql="INSERT INTO feedback VALUES('', '$_SESSION[login_user]', '$_POST[feedback]');";
				if(mysqli_query($conn,$sql))
				{
					$q="SELECT * FROM feedback ORDER BY feedback.id DESC";
					$res=mysqli_query($conn,$q);

				echo "<table class='table table-bordered'>";
					while ($row=mysqli_fetch_assoc($res)) 
					{

						echo "<tr>";
							echo "<td>"; echo $row['username']; echo "</td>";
							echo "<td>"; echo $row['comment']; echo "</td>";
						echo "</tr>";
					}
				echo "</table>";
				}

			}

			else
			{
				$q="SELECT * FROM feedback ORDER BY feedback.id DESC"; 
					$res=mysqli_query($conn,$q);

				echo "<table class='table table-bordered'>";
					while ($row=mysqli_fetch_assoc($res)) 
					{
						echo "<tr>";
							echo "<td>"; echo $row['username']; echo "</td>";
							echo "<td>"; echo $row['comment']; echo "</td>";
						echo "</tr>";
					}
				echo "</table>";
			}
		?>
	</div>
	</div>
	
</body>
</html>